<?php

declare(strict_types=1);

namespace CuyZ\Valinor\Tests\Unit\Mapper\Tree\Message;

use CuyZ\Valinor\Mapper\Tree\Message\MessagesFlattener;
use CuyZ\Valinor\Mapper\Tree\Node;
use CuyZ\Valinor\Tests\Fake\Mapper\Tree\FakeNode;
use CuyZ\Valinor\Tests\Fake\Mapper\Tree\Message\FakeErrorMessage;
use CuyZ\Valinor\Tests\Fake\Mapper\Tree\Message\FakeMessage;
use PHPUnit\Framework\TestCase;

final class MessagesFlattenerTest extends TestCase
{
    public function test_messages_are_filtered_and_can_be_iterated_through(): void
    {
        $messageA = new FakeMessage();
        $errorA = new FakeErrorMessage('some error message A');
        $errorB = new FakeErrorMessage('some error message B');

        $node = new Node(
            true,
            'nodeName',
            'some.node.path',
            'string',
            true,
            'some source value',
            'some value',
            [$errorB],
            [
                'foo' => FakeNode::withMessage($messageA),
                'bar' => FakeNode::withMessage($errorA),
            ]
        );

        $messages = [...(new MessagesFlattener($node))->errors()];

        self::assertCount(2, $messages);

        self::assertSame('some error message B', (string)$messages[0]);
        self::assertSame('some error message A', (string)$messages[1]);
    }
}
