<?php

class tokenGenerator{
    const HASH_ALGO = 'sha1';
    const HASH_SALT = 'AmakakeruRyuNoHirameki';
    const ITERATIONS_COUNT = 40000;

    public function get($value)
    {
        $token = '';
        for ($iteration = 0; $iteration < self::ITERATIONS_COUNT; $iteration++) {
            $token = hash(self::HASH_ALGO, $iteration . $token . self::HASH_SALT . $value);
        }

        return $token;
    }
}

$testObject = new tokenGenerator;
echo $testObject->get('a');

?>
