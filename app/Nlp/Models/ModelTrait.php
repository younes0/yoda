<?php

namespace Yoda\Nlp\Models;

use Yoda\Nlp\Libraries\AbstractLibrary;
use Yoda\Nlp\Tokenizer;
use Yoda\Nlp\Config;
use Yoda\Nlp\Tools;

trait ModelTrait
{
    // later: morphMany
    public function nlpClassed()
    {
        return $this->morphOne(NlpClassed::class, 'model');
    }

    public function scopeNotNlpClassed($query)
    {
        return $query->doesntHave('nlpClassed');
    }

    public function scopeIsNlpClassed($query, $model = null)
    {
        if ($model) {
            return $query->whereHas('nlpClassed', function($q) use ($model) {
                $q->where('nlp_model', $model);
            });
        
        } else {
            return $query->has('nlpClassed');
        }
    }

// Non-Eloquent
// ---------------------------------------------

    public function nlpClassify($model)
    {
        $content = $this->getNlpProps()['content'];
        if ( !$content) return false;

        $config  = new Config($model);
        $results = Tools::getLibrary($config->method)
            ->setup($config)
            ->classify($content);

        $this->nlpClassed()->delete();
        $this->nlpClassed()->save(new NlpClassed([
            'nlp_model' => $model,
            'method'    => $config->method,
            'class'     => $results['class'],
            'score'     => $results['score'],
        ]));

        // refresh relations
        $this->load('nlpClassed');

        return $this;
    }

    public function nlpDoc()
    {
        $props = $this->getNlpProps();

        return Document::whereIn('domain', $props['domains'])
            ->where('content', $props['content'])
            ->first();
    }

    public function createNlpDoc(Array $array)
    {
        return Document::create(array_merge($this->getNlpProps(), $array));
    }

    public function tokenize($method)
    {
        return Tokenizer::tokenize(
            $this->getNlpProps()['content'], 
            $method
        );
    }
}
