<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 20/12/2016
 * Time: 1:20 AM
 */

namespace Toolkits\LaravelBuilder\Parsers;


use Symfony\Component\Yaml\Yaml as SymfonyYaml;

class Yaml
{


    public static function make()
    {
        return new static;
    }


    /**
     * Parses supplied YAML contents in to a PHP array.
     * @param string $contents YAML contents to parse.
     * @return array The YAML contents as an array.
     */
    public function parse($contents)
    {
        return SymfonyYaml::parse($contents);
    }



    /**
     * Parses YAML file contents in to a PHP array.
     * @param string $fileName File to read contents and parse.
     * @return array The YAML contents as an array.
     */
    public function parseFile($fileName)
    {
        $contents = file_get_contents($fileName);
        return $this->parse($contents);
    }

    /**
     * Dumps a PHP value to a YAML string.
     *
     * The dump method, when supplied with an array, will do its best
     * to convert the array into friendly YAML.
     *
     * @param mixed $input The PHP value
     * @param int $inline The level where you switch to inline YAML
     * @param int $indent The amount of spaces to use for indentation of nested nodes
     * @param int $flags A bit field of DUMP_* constants to customize the dumped YAML string
     * @param bool $objectSupported
     * @return string A YAML string representing the original PHP value
     */
    public function dump($input, $inline = 20, $indent = 4, $flags = 0, $objectSupported = true)
    {
        return SymfonyYaml::dump($input, $inline, $indent, $flags, $objectSupported);
    }


    public function toString($input, $inline = 20, $indent = 4, $flags = 0, $objectSupported = true)
    {
        return $this->dump($input, $inline, $indent, $flags, $objectSupported);
    }

}