<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);


namespace Ruga\Asset\Test;

use Laminas\ServiceManager\ServiceManager;

/**
 * @author                 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 */
class SkeletonTest extends \Ruga\Asset\Test\PHPUnit\AbstractTestSetUp
{
    public function testCanSetContainer(): void
    {
        $this->expectNotToPerformAssertions();
    }
}
