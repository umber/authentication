<?php

declare(strict_types=1);

namespace Umber\Authentication\Tests\Unit\Token\Key\Loader;

use Umber\Authentication\Token\Key\Loader\FileKeyLoader;

use PHPUnit\Framework\TestCase;

/**
 * @group unit
 * @group authentication
 *
 * @covers \Umber\Authentication\Token\Key\Loader\FileKeyLoader
 */
final class FileKeyLoaderTest extends TestCase
{
    /**
     * @test
     */
    public function canLoadFileContents(): void
    {
        $file = sprintf('/tmp/FileKeyLoaderTest_%s.txt', __METHOD__);

        touch($file);
        file_put_contents($file, 'file-contents');

        $loader = new FileKeyLoader($file);

        self::assertEquals('file-contents', $loader->load());
    }
}
