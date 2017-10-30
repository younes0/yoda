<?php

namespace Yoda\Models;

trait TaggableTrait
{
    public function tagged()
    {
        return $this->morphMany(Tagged::class, 'taggable');
    }

    public function getTags($asSlug = false)
    {
        $tags = [];

        foreach ($this->tagged as $tagged) {
            $tag = $tagged->getTag();
            
            $tags[] = $asSlug ? $tag->getSlug() : $tag;
        }

        return collect($tags);
    }

    public function tag(Tag $tag, $source = 'nlp')
    {
        return $this->tagged()->save(new Tagged([
            'tag_domain'   => $tag->domain,
            'tag_codename' => $tag->codename,
            'source'       => $source,
        ]));
    }

    public function untag($source = null)
    {
        $tagged = $source 
            ? $this->tagged()->where('source', $source)->get()
            : $this->tagged;

        foreach ($tagged as $t) {
            $t->delete();
        }

        return $this;
    }
    
    public function tagFromNlp()
    {
        if (
            $this->nlpClassed 
            && ($tag = Tag::findByNlpClassed($this->nlpClassed))
        ) {
            $this->tag($tag, 'nlp'); 

        } else {
            $this->untag('nlp');
        }

        return $this;
    }

    public function scopeByTag($query, Tag $tag)
    {
        return $query->whereHas('tagged', function($sub) use ($tag) {
            return $sub
                ->where('tag_domain', $tag->domain)
                ->where('tag_codename', $tag->codename);
        });
    }
}
