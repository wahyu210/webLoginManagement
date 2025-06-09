<?php
namespace MochamadWahyu\Phpmvc\App {
    function header(string $value)
    {
        echo $value;
    }

}
namespace MochamadWahyu\Phpmvc\Service {
    function setcookie(string $name, string $value)
    {
        echo "$name: $value";
    }
}