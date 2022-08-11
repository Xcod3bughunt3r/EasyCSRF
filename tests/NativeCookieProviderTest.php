<?php

use PHPUnit\Framework\TestCase;
use EasyCSRF\EasyCSRF;
use EasyCSRF\Exceptions\InvalidCsrfTokenException;
use EasyCSRF\NativeCookieProvider;

class NativeCookieProviderTest extends TestCase
{
    protected $easyCSRF;

    protected function setUp(): void
    {
        $_SERVER['REMOTE_ADDR'] = '1.1.1.1';
        $_SERVER['HTTP_USER_AGENT'] = 'useragent';

        $sessionProvider = new NativeCookieProvider();
        $this->easyCSRF = new EasyCSRF($sessionProvider);
    }

    public function testGenerate()
    {
        $token = $this->easyCSRF->generate('testing');

        $this->assertNotNull($token);
    }

    public function testCheck()
    {
        $token = $this->easyCSRF->generate('testing');
        $this->easyCSRF->check('testing', $token);

        $this->assertNull($_COOKIE['easycsrf_testing']);
    }

    public function testCheckMultiple()
    {
        $token = $this->easyCSRF->generate('testing');
        $this->easyCSRF->check('testing', $token, null, true);

        $this->assertNotNull($_COOKIE['easycsrf_testing']);
    }

    public function testExceptionMissingFormToken()
    {
        $this->expectException(InvalidCsrfTokenException::class);

        $this->easyCSRF->check('testing', '');
    }

    public function testExceptionMissingSessionToken()
    {
        $this->expectException(InvalidCsrfTokenException::class);

        $this->easyCSRF->check('testing', '0123456789');
    }

    public function testExceptionOrigin()
    {
        $this->expectException(InvalidCsrfTokenException::class);

        $token = $this->easyCSRF->generate('testing');
        $_SERVER['REMOTE_ADDR'] = '2.2.2.2';
        $this->easyCSRF->check('testing', $token);
    }

    public function testExceptionInvalidToken()
    {
        $this->expectException(InvalidCsrfTokenException::class);

        $this->easyCSRF->generate('testing');
        $this->easyCSRF->check('testing', '0123456789');
    }

    public function testExceptionExpired()
    {
        $this->expectException(InvalidCsrfTokenException::class);

        $token = $this->easyCSRF->generate('testimg');
        sleep(2);
        $this->easyCSRF->check('testing', $token, 1);
    }
}

?>