<?php declare(strict_types=1);

namespace App\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

final class IOHelper
{
    /** @var Command */
    private $command;

    /** @var InputInterface */
    private $input;

    /** @var OutputInterface */
    private $output;

    /**
     * @param Command $command
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function __construct(Command $command, InputInterface $input, OutputInterface $output)
    {
        $this->command = $command;
        $this->input = $input;
        $this->output = $output;
    }

    /**
     * @return bool
     */
    public function isDryRun(): bool
    {
        try {
            return (bool) $this->input->getOption('dry-run');
        } catch (\InvalidArgumentException $e) {
            return false;
        }
    }

    /**
     * @param string $message
     * @param bool $default
     *
     * @return bool
     */
    public function askConfirmation(string $message, bool $default = true): bool
    {
        /** @var \Symfony\Component\Console\Helper\QuestionHelper $questionHelper */
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $questionHelper = $this->command->getHelper('question');

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $questionHelper->ask($this->input, $this->output, new ConfirmationQuestion($message, $default));
    }

    /**
     * @param string|array $messages
     * @param bool $newLine
     */
    public function write($messages, $newLine = false): void
    {
        $this->output->write($messages, $newLine);
    }

    /**
     * @param string|array $messages
     * @param int $options
     */
    public function writeln($messages, int $options = 0): void
    {
        $this->output->writeln($messages, $options);
    }

    /**
     * @param array $messages
     * @param int $indentation
     */
    public function listing(array $messages, int $indentation = 0): void
    {
        $messages = array_map(function ($message) use ($indentation) {
            return sprintf(str_repeat(' ', $indentation).' * %s', $message);
        }, $messages);

        $this->write(PHP_EOL);
        $this->writeln($messages);
        $this->write(PHP_EOL);
    }

    /**
     * @param callable $callable
     */
    public function forceOutput(callable $callable): void
    {
        $verbosity = $this->output->getVerbosity();
        $this->output->setVerbosity(OutputInterface::VERBOSITY_NORMAL);

        $callable();

        $this->output->setVerbosity($verbosity);
    }
}
