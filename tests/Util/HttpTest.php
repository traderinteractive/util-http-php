<?php

namespace TraderInteractive\Util;

use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \TraderInteractive\Util\Http
 */
final class HttpTest extends TestCase
{
    /**
     * @test
     * @group unit
     * @covers ::parseHeaders
     *
     * @return void
     */
    public function parseHeadersBasicUsage()
    {
        $headers = 'Content-Type: text/json';
        $result = Http::parseHeaders($headers);
        $this->assertSame(['Content-Type' => 'text/json'], $result);
    }

    /**
     * @test
     * @group unit
     * @covers ::parseHeaders
     *
     * @return void
     */
    public function parseHeadersMalformed()
    {
        try {
            $headers = "&some\r\nbad+headers";
            Http::parseHeaders($headers);
            $this->fail('No exception thrown');
        } catch (\Exception $e) {
            $this->assertSame('Unsupported header format: &some', $e->getMessage());
        }
    }

    /**
     * Verifies parseHeaders retains the functionality of http_parse_headers()
     *
     * @test
     * @group unit
     * @covers ::parseHeaders
     *
     * @return void
     */
    public function parseHeadersPeclHttpFunctionality()
    {
        $headers = <<<EOT
HTTP/1.1 200 OK\r\n
content-type: text/html; charset=UTF-8\r\n
Server: Funky/1.0\r\n
Set-Cookie: foo=bar\r\n
Set-Cookie: baz=quux\r\n
Set-Cookie: key=value\r\n
EOT;
        $expected = [
            'Response Code' => 200,
            'Response Status' => 'OK',
            'Content-Type' => 'text/html; charset=UTF-8',
            'Server' => 'Funky/1.0',
            'Set-Cookie' => ['foo=bar', 'baz=quux', 'key=value'],
        ];
        $result = Http::parseHeaders($headers);
        $this->assertSame($expected, $result);
    }

    /**
     * Verifies Request Method and Request Url are set properly
     *
     * @test
     * @group unit
     * @covers ::parseHeaders
     *
     * @return void
     */
    public function parseHeadersMethodAndUrlSet()
    {
        $headers = <<<EOT
GET /file.xml HTTP/1.1\r\n
Host: www.example.com\r\n
Accept: */*\r\n
EOT;
        $expected = [
            'Request Method' => 'GET',
            'Request Url' => '/file.xml',
            'Host' => 'www.example.com',
            'Accept' => '*/*'
        ];
        $result = Http::parseHeaders($headers);
        $this->assertSame($expected, $result);
    }

    /**
     * Verifies that the rawHeaders string cannot be whitespace.
     *
     * @test
     * @group unit
     * @covers ::parseHeaders
     *
     * @return void
     */
    public function parseHeadersWhitespace()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('$rawHeaders cannot be whitespace');
        Http::parseHeaders('');
    }

    /**
     * @test
     * @covers ::buildQueryString
     *
     * @return void
     */
    public function buildQueryStringBasicUse()
    {
        $data = [
            'foo' => 'bar',
            'baz' => 'boom',
            'cow' => 'milk',
            'php' => 'hypertext processor',
            'theFalse' => false,
            'theTrue' => true
        ];

        $this->assertSame(
            'foo=bar&baz=boom&cow=milk&php=hypertext%20processor&theFalse=false&theTrue=true',
            Http::buildQueryString($data)
        );
    }

    /**
     * @test
     * @covers ::buildQueryString
     *
     * @return void
     */
    public function buildQueryStringMultiValue()
    {
        $data = ['param1' => ['value', 'another value'], 'param2' => 'a value'];

        $this->assertSame('param1=value&param1=another%20value&param2=a%20value', Http::buildQueryString($data));
    }

    /**
     * @test
     * @covers ::buildQueryString
     *
     * @return void
     */
    public function buildQueryStringComplexValues()
    {
        $this->assertSame(
            'a%20b%20c=1%242%283&a%20b%20c=4%295%2A6',
            Http::buildQueryString(['a b c' => ['1$2(3', '4)5*6']])
        );
    }

    /**
     * Verifies Multi Parameter Method can handle a normal url
     *
     * @test
     * @group unit
     * @covers ::getQueryParams
     *
     * @return void
     */
    public function getQueryParamsNormal()
    {
        $url = 'http://foo.com/bar/?otherStuff=green&stuff=yeah&moreStuff=rock&moreStuff=jazz&otherStuff=blue&'
             . 'otherStuff=black';
        $expected = [
            'otherStuff' => ['green', 'blue', 'black'],
            'stuff' => ['yeah'],
            'moreStuff' => ['rock', 'jazz'],
        ];
        $result = Http::getQueryParams($url);
        $this->assertSame($expected, $result);
    }

    /**
     * Verifies Multi Parameter Method can handle a url with an empty parameter
     *
     * @test
     * @group unit
     * @covers ::getQueryParams
     *
     * @return void
     */
    public function getQueryParamsEmptyParameter()
    {
        $url = 'http://foo.com/bar/?stuff=yeah&moreStuff=&moreStuff=jazz&otherStuff';
        $expected = [
            'stuff' => ['yeah'],
            'moreStuff' => ['', 'jazz'],
            'otherStuff' => [''],
        ];
        $result = Http::getQueryParams($url);
        $this->assertSame($expected, $result);
    }

    /**
     * Verifies multi parameter method with a garbage query string
     *
     * @test
     * @group unit
     * @covers ::getQueryParams
     *
     * @return void
     */
    public function getQueryParamsGarbage()
    {
        $this->assertSame([], Http::getQueryParams('GARBAGE'));
    }

    /**
     * @test
     * @covers ::getQueryParams
     *
     * @return void
     */
    public function getQueryParamsWithCollapsed()
    {
        $result = Http::getQueryParams('http://foo.com/bar/?stuff=yeah&moreStuff=mhmm', ['stuff', 'notThere']);
        $this->assertSame(['stuff' => 'yeah', 'moreStuff' => ['mhmm']], $result);
    }

    /**
     * @test
     * @covers ::getQueryParams
     *
     * @return void
     */
    public function getQueryParamsCollapsedMoreThanOneValue()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Parameter \'stuff\' had more than one value but in $collapsedParams');
        Http::getQueryParams('http://foo.com/bar/?stuff=yeah&stuff=boy&moreStuff=mhmm', ['stuff']);
    }

    /**
     * @test
     * @covers ::getQueryParamsCollapsed
     *
     * @return void
     */
    public function getQueryParamsCollapsed()
    {
        $url = 'http://foo.com/bar/?boo=1&foo=bar&boo=2';
        $actual = Http::getQueryParamsCollapsed($url, ['boo']);
        $this->assertSame(['boo' => ['1', '2'], 'foo' => 'bar'], $actual);
    }

    /**
     * @test
     * @covers ::getQueryParamsCollapsed
     *
     * @return void
     */
    public function getQueryParamsCollapsedUnexpectedArray()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Parameter \'boo\' is not expected to be an array, but array given');
        $url = 'http://foo.com/bar/?boo=1&foo=bar&boo=2';
        Http::getQueryParamsCollapsed($url);
    }

    /**
     * Verifies multi parameter method with a garbage query string
     *
     * @test
     * @covers ::getQueryParamsCollapsed
     *
     * @return void
     */
    public function getQueryParamsCollapsedGarbage()
    {
        $this->assertSame([], Http::getQueryParamsCollapsed('GARBAGE'));
    }

    /**
     * Verifies Multi Parameter Method can handle a url with an empty parameter
     *
     * @test
     * @covers ::getQueryParamsCollapsed
     *
     * @return void
     */
    public function getQueryParamsCollapsedEmptyParameter()
    {
        $url = 'http://foo.com/bar/?stuff=yeah&moreStuff=&moreStuff=jazz&otherStuff';
        $expected = ['stuff' => 'yeah', 'moreStuff' => ['', 'jazz'], 'otherStuff' => ''];
        $this->assertSame($expected, Http::getQueryParamsCollapsed($url, ['moreStuff']));
    }
}
