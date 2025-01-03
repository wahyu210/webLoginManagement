<?php   
namespace MochamadWahyu\Phpmvc\MiddleWare;


use PHPUnit\Framework\TestCase;

class MustLoginMiddlewareTest extends TestCase
{
    private MustLoginMiddleware $mustLoginMiddleware;

    protected function setUp(): void
    {
        $this->mustLoginMiddleware = new MustLoginMiddleware();
        
    }
    
    public function testBefore(){
        
    }
    
    
}