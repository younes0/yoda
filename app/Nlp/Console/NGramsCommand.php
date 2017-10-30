<?php

namespace Yoda\Nlp\Console;

use Illuminate\Console\Command;
use Yoda\Nlp\{
    Models\Document,
    Models\NGram,
    Transformers\FrenchStopwords,
    Transformers\YodaStopwords
};


class NGramsCommand extends Command 
{
    protected $signature = 'nlp:ngrams'; 

    protected $description = 'NGrams generate';

    const MIN_OCCUR = 20;
    const MIN_LENGTH = 12;

    public function handle()
    {
        \DB::connection('nlp')->table('ngrams')->truncate();

        $documents = Document::limit(10000)->orderBy('id')->get();
        $ngrams = [];

        foreach ($documents as $doc) {
            $tokens = array_filter(explode(' ', mb_strtolower($doc->content)));

            foreach ($tokens as $index => $token) {
                $prefix = $tokens[$index-1] ?? null;
                $suffix = $tokens[$index+1] ?? null;
         
                $keys = [
                    $prefix.' '.$token.' '.$suffix,
                    $prefix.' '.$token,
                    $token.' '.$suffix,
                ];

                foreach ($keys as $key) {
                    $ngrams[$key] = isset($ngrams[$key]) ? $ngrams[$key]+1 : 0;
                }
            }
        } 

        $stopwords = array_merge(FrenchStopwords::$stopwords, YodaStopwords::$stopwords);

        $ngrams = array_where($ngrams, function($key, $value) use ($stopwords) {
            $key = preg_replace('~[^\p{L}]++~u', ' ', $key);

            foreach ($stopwords as $word) {
                if (starts_with($key, $word.' ') || ends_with($key, ' '.$word)) {
                    return false;
                }
            } 

            return (($value > self::MIN_OCCUR) && (strlen($key) > self::MIN_LENGTH));
        });

        $data = [];
        foreach ($ngrams as $key => $value) {
            $data[] = ['id' => $key, 'count' => $value];
        }

        foreach (array_chunk($data, 1000) as $chunk) {
            NGram::insert($chunk);
        }

        $this->info('done');
    }
}
