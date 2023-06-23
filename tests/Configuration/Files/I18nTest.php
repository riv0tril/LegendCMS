<?php

namespace Tests\Configuration\Files;

use App\Application\Configuration\Exception\AttributeException;
use App\Application\Configuration\Files\I18n;
use App\Application\Configuration\Files\I18nDto;
use Tests\TestCase;


class I18nTest extends TestCase
{
    public function testReadValidFormat(): void
    {
        $debug = new I18n();
        $debug->setBase(__DIR__ . '/dist/i18n/');
        $debug->setFile('i18n.json');
        $debug->setSchema('schemas/i18n.schema.json');
        $dto = $debug->parse();
        $this->assertInstanceOf(I18nDto::class, $dto);

        $this->assertEquals($dto->getCurrent(), 'EN');
        $this->assertEquals($dto->getLocales(), ['ES', 'EN', 'BR']);
    }

    public function testSetInvalidCurret(): void
    {
        $debug = new I18n();
        $debug->setBase(__DIR__ . '/dist/i18n/');
        $debug->setFile('i18n.json');
        $debug->setSchema('schemas/i18n.schema.json');
        $dto = $debug->parse();
        $this->assertInstanceOf(I18nDto::class, $dto);
        $this->expectException(AttributeException::class);
        $this->expectExceptionMessage("Locale: YY is not supported");
        $dto->setCurrent('YY');
    }

    public function testSuccessWrite(): void
    {
        $debug = new I18n();
        $debug->setBase(__DIR__ . '/dist/i18n/');
        $debug->setFile('i18n.json');
        $debug->setSchema('schemas/i18n.schema.json');
        $dto = $debug->parse();

        $dto->setCurrent('ES');
        $dto->addLocale('YY');
        $count = count($dto->getLocales());
        $debug->save($dto);

        $content = $debug->read();
        $this->assertEquals($dto->getCurrent(), $content['current']);
        $this->assertEquals('YY', array_pop($content['locales']));
        $this->assertEquals(4, $count);
        //revert to original state
        $dto->setCurrent('EN');
        $dto->deleteLocale('YY');
        $debug->save($dto);
        /*
        $debug->save($dto);
        $refresh = $debug->read();

        $aLog = $refresh['log'];
        $aDet = $refresh['details'];
        $aDbg = $refresh['debug'];
        $aLvl = $refresh['logger']['level'];
        $aPat = $refresh['logger']['path'];

        $this->assertEquals(true, $aLog);
        $this->assertEquals(true, $aDet);
        $this->assertEquals(true, $aDbg);
        $this->assertEquals('DEBUG', $aLvl);
        $this->assertEquals('test.log', $aPat);

        //revert to original state
        $dto->setLog(false);
        $dto->setDetails(false);
        $dto->setDebug(false);
        $dto->setLoggerLevel('INFO');
        $dto->setLoggerPath('app.log');
        $debug->save($dto);*/
    }
}
