<?php

namespace Yoda\Models;

use Yeb\Laravel\ExtendedModel;
use Yoda\Nlp\Models\NlpClassed;

class Tag extends ExtendedModel 
{
    public $connection = 'items';

    public $table = 'tags';

    protected $guarded = ['oid'];

    protected $casts = [
        'is_primary'  => 'boolean',
        'nlp_classes' => 'array',
    ];

    public function getSlug()
    {
        return str_slug(trim($this->codename));
    }

    public function getNlpClasses()
    {
        return ($this->nlp_classes && !empty($this->nlp_classes))
            ? $this->nlp_classes
            : (array) $this->codename;
    }

    // later: domain specific
    public function getNlpClassed()
    {
        return NlpClassed::whereIn('class', $this->getNlpClasses())
            // ->where('domain', $this->domain)
            ->where('nlp_model', 'law-fr')
            ->get();
    }

    static public function findByNlpClassed(NlpClassed $nlpClassed)
    {
        // later: domain specific
        $query = Tag::where('domain', $nlpClassed->nlp_model);
        $class = $nlpClassed->class;

        if (
            $tag = $query->where('codename', $class)->first() or
            $tag = $query->where('codename', 'LIKE', "%'".$class."'%")->first()
        ) {
            return $tag;
        }

        return null;
    }
}
