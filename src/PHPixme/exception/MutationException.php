<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 5/18/2016
 * Time: 1:55 PM
 */

namespace PHPixme\exception;
use PHPixme\ClosedTrait;
use PHPixme\ImmutableConstructorTrait;

/**
 * Class MutationException
 * If you get one of these, you tried to mutate something that is immutable. Don't do that.
 * @internal
 * @package PHPixme\exception
 */
class MutationException extends \Exception
{
  use ClosedTrait, ImmutableConstructorTrait;
  /**
   * @throws \Exception
   */
  public function __construct() {
    $this->assertOnce();
    $this->message = static::quotes[mt_rand(0, count(static::quotes) - 1)];
  }
  const quotes = [
    "You seem to like mutability, so I made several messages for you each time you call this."
    , "Its people like you who turn PHP into a BDSM language."
    , "\"...the problem with object-oriented languages is they’ve got all this implicit environment that they carry around with them. You wanted a banana but what you got was a gorilla holding the banana and the entire jungle.\" ~ Joe Armstrong, creator of Erlang"
    , "I'm sorry, I can't let you do that, Dave."
    , 'You think this is a game?'
    , 'Say "what" again, I dare you!'
    , 'O thou pernicious caitiff.'
    , 'Nice job breaking it, "hero".'
    , "Don't-think-about-it-don't-think-about-it..."
    , "Well done. Here come the test results: \"You are a horrible person.\" That's what it says: a horrible person. We weren't even testing for that."
    , "Did you know that people with guilty consciences are more easily startled by loud noises? [train horn] I'm sorry, I don't know why that went off. Anyway, just an interesting science fact."
    , " Excellent! You're a predator and these tests are your prey. Speaking of which, I was researching sharks for an upcoming test. Do you know who else murders people who are only trying to help them? Did you guess \"sharks\"? Because that's wrong. The correct answer is \"nobody\". Nobody but you is that pointlessly cruel."
    , "Do you know who I am? I'm the man who's gonna burn your house down! With the lemons!  I'm gonna get my engineers to invent a combustible lemon that burns your house down!"
    , "To the last, I grapple with thee; From Hell's heart, I stab at thee; For hate's sake, I spit my last breath at thee."
    , "\"The path of the righteous man is beset on all sides by the inequities of the selfish and the tyranny of evil men. Blessed is he who, in the name of charity and good will, shepherds the weak through the valley of the darkness, for he is truly his brother's keeper and the finder of lost children. And I will strike down upon thee with great vengeance and furious anger those who attempt to poison and destroy My brothers. And you will know I am the Lord when I lay My vengeance upon you.\"... I'm trying real hard to be the shepherd."
    , "if you keep going the way you are now... you're gonna have a bad time."
    , "it's a beautiful day outside. birds are singing, flowers are blooming... on days like these, kids like you.... Ｓｈｏｕｌｄ ｂｅ ｂｕｒｎｉｎｇ ｉｎ ｈｅｌｌ."
    , 'Oh dear, are you serious...? You are an "interesting" child.'
    , 'Sounds like only fire lives here now.'
  ];
  
}