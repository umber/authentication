<?php

declare(strict_types=1);

namespace Umber\Authentication\Tests\Unit\Token\Key\Loader;

use Umber\Authentication\Token\Key\Loader\FileKeyLoader;

use PHPUnit\Framework\TestCase;

/**
 * {@inheritdoc}
 */
final class FileKeyLoaderTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     * @group authentication
     *
     * @covers \Umber\Authentication\Token\Key\Loader\FileKeyLoader
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
