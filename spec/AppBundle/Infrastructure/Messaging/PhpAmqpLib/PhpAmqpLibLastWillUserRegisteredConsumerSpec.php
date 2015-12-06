<?php

namespace spec\AppBundle\Infrastructure\Messaging\PhpAmqpLib;

use League\Tactician\Setup\QuickStart;
use Lw\Gamification\Command\SignupCommand;
use PhpAmqpLib\Message\AMQPMessage;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PhpAmqpLibLastWillUserRegisteredConsumerSpec extends ObjectBehavior
{
    public function let()
    {
        $commandBus = QuickStart::create([
            SignupCommand::class => new CommandHandler()
        ]);

        $this->beConstructedWith($commandBus);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\Infrastructure\Messaging\PhpAmqpLib\PhpAmqpLibLastWillUserRegisteredConsumer');
    }

    public function it_should_return_false_if_message_has_a_type_different_from_last_wishes_user_registered()
    {
        $this->execute(new AMQPMessage('test', ['type' => 'test']))->shouldBe(false);
    }

    public function it_should_execute_the_signup_command_when_a_last_wishes_user_registered_event_is_received()
    {
        $message = new AMQPMessage(json_encode(['event_body' => json_encode(['user_id' => ['id' => 'test']])]), ['type' => 'Lw\Domain\Model\User\UserRegistered']);

        $this->execute($message)->shouldBe(true);
    }
}

class CommandHandler
{
    public function handle(SignupCommand $command)
    {
        // Noop
    }
}
