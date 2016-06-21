<?php

namespace League\Omnipay\Common;

use League\Omnipay\Tests\TestCase;

class LocaleTest extends TestCase
{

    public function testConstruct()
    {
        $locale = new Locale('en', 'us');
        $this->assertSame('en-us', $locale->getLocale());
        $this->assertSame('en', $locale->getPrimaryLanguage());
        $this->assertSame('us', $locale->getRegion());
        $this->assertSame('en-us', (string) $locale);
    }

    public function testConstructWithoutRegion()
    {
        $locale = new Locale('en');
        $this->assertSame('en', $locale->getPrimaryLanguage());
        $this->assertNull($locale->getRegion());
    }

    public function testParse()
    {
        $locale = Locale::parse('en-us');
        $this->assertSame('en', $locale->getPrimaryLanguage());
        $this->assertSame('us', $locale->getRegion());
    }

    public function testParseUnderscore()
    {
        $locale = Locale::parse('en_us');
        $this->assertSame('en', $locale->getPrimaryLanguage());
        $this->assertSame('us', $locale->getRegion());
    }

    public function testParseWithoutRegion()
    {
        $locale = Locale::parse('en');
        $this->assertSame('en', $locale->getPrimaryLanguage());
        $this->assertNull($locale->getRegion());
    }

    public function testLowercase()
    {
        $locale = Locale::parse('En-US');
        $this->assertSame('en-us', $locale->getLocale());
        $this->assertSame('en', $locale->getPrimaryLanguage());
        $this->assertSame('us', $locale->getRegion());
    }
}
