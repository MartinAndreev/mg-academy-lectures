<?php

namespace System\Core\Interfaces;

interface DIInjectable {

    public function setDI(\System\DI $di);

    public function getDI();
}
