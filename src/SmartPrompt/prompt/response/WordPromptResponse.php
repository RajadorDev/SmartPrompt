<?php

declare (strict_types=1);

/***
 *   
 * Rajador Developer
 * 
 * ▒█▀▀█ ░█▀▀█ ░░░▒█ ░█▀▀█ ▒█▀▀▄ ▒█▀▀▀█ ▒█▀▀█ 
 * ▒█▄▄▀ ▒█▄▄█ ░▄░▒█ ▒█▄▄█ ▒█░▒█ ▒█░░▒█ ▒█▄▄▀ 
 * ▒█░▒█ ▒█░▒█ ▒█▄▄█ ▒█░▒█ ▒█▄▄▀ ▒█▄▄▄█ ▒█░▒█
 * 
 * GitHub: https://github.com/RajadorDev
 * 
 * Discord: rajadortv
 * 
 * 
**/

namespace SmartPrompt\prompt\response;

use pocketmine\utils\TextFormat;

class WordPromptResponse extends PromptResponse
{

    /** @var string[] */
    protected $responses;

    /** @var bool */
    protected $regular, $onlyFirstWord;

    /**
     * @param string[] $words The words that can be the response
     * @param callable $whenRespond (Player, string) : void (called when a right response is sended)
     * @param boolean $regular 
     * @param boolean $onlyFirstWord If true, only the first word in the message will be checked
     */
    public static function create(array $words, callable $whenRespond, bool $regular = false, bool $onlyFirstWord = true)
    {
        return new WordPromptResponse(
            $words,
            $whenRespond,
            $regular,
            $onlyFirstWord
        );
    }

    /**
     * @param string[] $words The words that can be the response
     * @param callable $whenRespond (Player, string) : void (called when a right response is sended)
     * @param boolean $regular 
     * @param boolean $onlyFirstWord If true, only the first word in the message will be checked
     */
    public function __construct(array $words, callable $whenRespond, bool $regular = false, bool $onlyFirstWord = true)
    {
        parent::__construct($whenRespond);
        $this->responses = $words;
        $this->regular = $regular;
        $this->onlyFirstWord = $onlyFirstWord;
    }

    public function isResponse(string $response) : bool 
    {
        $words = $this->responses;
        if (!$this->regular)
        {
            $transform = static function (string $text) : string {
                return strtolower(TextFormat::clean($text));
            };
            $response = strtolower($response);
            $words = array_map(
                static function (string $text) use ($transform) : string {
                    return $transform($text);
                },
                $words
            );
        }
        if ($this->onlyFirstWord)
        {
            $response = explode(' ', $response)[0];
        }
        return in_array($response, $words);
    }
    
}