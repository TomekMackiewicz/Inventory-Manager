<?php

namespace InventoryBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class InventoryBundle extends Bundle {

  public function getParent() {
    return 'FOSUserBundle';
  }
}
