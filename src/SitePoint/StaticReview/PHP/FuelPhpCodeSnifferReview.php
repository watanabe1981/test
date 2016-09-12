<?php

/*
 * Fuel
 *
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.8
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2014 Fuel Development Team
 * @link       http://fuelphp.com
 */

/*
 * This file is part of StaticReview
 *
 * Copyright (c) 2016 Isamu Watanabe <@i.watanabe_>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace SitePoint\StaticReview\PHP;

use StaticReview\File\FileInterface;
use StaticReview\Reporter\ReporterInterface;
use StaticReview\Review\AbstractReview;
class FuelPhpCodeSnifferReview extends AbstractReview
{
    protected $options = array();

    /**
     * Gets the value of an option.
     *
     * @param  string $option
     * @return string
     */
    public function getOption($option)
    {
        return $this->options[$option];
    }

    /**
     * Gets a string of the set options to pass to the command line.
     *
     * @return string
     */
    public function getOptionsForConsole()
    {
        $builder = '';

        foreach ($this->options as $option => $value)
        {
            $builder .= '--' . $option;

            if ($value) {
                $builder .= '=' . $value;
            }

            $builder .= ' ';
        }

        return $builder;
    }

    /**
     * Adds an option to be included when running PHP_CodeSniffer. Overwrites the values of options with the same name.
     *
     * @param  string               $option
     * @param  string               $value
     * @return PhpCodeSnifferReview
     */
    public function setOption($option, $value)
    {
        if ($option === 'report')
        {
            throw new \RuntimeException('"report" is not a valid option name.');
        }

        $this->options[$option] = $value;

        return $this;
    }

    /**
     * Determins if a given file should be reviewed.
     *
     * @param  FileInterface $file
     * @return bool
     */
    public function canReview(FileInterface $file)
    {
        return ($file->getExtension() === 'php');
    }

    /**
     * Checks PHP files using PHP_CodeSniffer.
     */
    public function review(ReporterInterface $reporter, FileInterface $file)
    {
        //$cmd = 'vendor/bin/phpcs --report=json ';
        $cmd = 'vendor/eviweb/fuelphp-phpcs/bin/fuelphpcs --standard=FuelPHP --extensions=php --ignore=fuel/app/tmp/* --ignore=fuel/app/logs/* --ignore=fuel/app/cache/* --ignore=fuel/app/views/* --ignore=fuel/app/config/* --ignore=fuel/app/tests_c/* --ignore=fuel/app/tests/* --ignore=fuel/app/lang/*  --ignore=src/* --report=json ';

        if ($this->getOptionsForConsole())
        {
            $cmd .= $this->getOptionsForConsole();
        }

        $cmd .= $file->getFullPath();

        $process = $this->getProcess($cmd);
        $process->run();

        if ( ! $process->isSuccessful())
        {

            // Create the array of outputs and remove empty values.
            $output = json_decode($process->getOutput(), true);

            $filter = function ($acc, $file)
            {
                if ($file['errors'] > 0 || $file['warnings'] > 0)
                {
                    return $acc + $file['messages'];
                }
            };

            foreach (array_reduce($output['files'], $filter, array()) as $error)
            {
                $message = $error['message'] . ' on line ' . $error['line'];
                $reporter->warning($message, $this, $file);
            }
        }
    }
}

