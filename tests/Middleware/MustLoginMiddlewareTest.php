<?php   
namespace MochamadWahyu\Phpmvc\MiddleWare;


use PHPUnit\Framework\TestCase;

class MustLoginMiddlewareTest extends TestCase
{
    private MustLoginMiddleware $mustLoginMiddleware;

    protected function setUp(): void
    {
        $this->mustLoginMiddleware = new MustLoginMiddleware();
        putenv("mode=test");
    }
    
    public function testBefore(){
        $this->mustLoginMiddleware->before();
        $this->expectOutputString('');
    }
    
}