<?php

namespace Yoda\Nlp\Testers;

use Yoda\Nlp\Models\Document;

class IsLawFr extends AbstractTester 
{
    protected function getTrainingDocs()
    {
        return $this->getAllDocs();
        return $this->getAllDocs()->filter(function($item) {
            return !($item->id % 2);
        });
    }
    
    protected function getTestingDocs()
    {
        return $this->getAllDocs();
        return $this->getAllDocs()->filter(function($item) {
            return !!($item->id % 2);
        });
    }

    protected function getAllDocs()
    {
        $docs = collect();

        foreach ($this->config->domains as $domain) {
            $docs = $docs->merge(
                Document::where('domain', $domain)->limit(7000)->get()
            );
        }

        foreach ($docs as $doc) {
            $doc->class = ($doc->domain === 'law-fr') ? 'true' : 'false'; 
        }

        return $docs;
    }
}
