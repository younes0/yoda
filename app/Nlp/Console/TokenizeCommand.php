<?php

namespace Yoda\Nlp\Console;

use Illuminate\Console\Command;
use Yoda\Nlp\Models\Document;
use DB;

class TokenizeCommand extends Command
{
    protected $signature = 'nlp:tokenize {document?}'; 
    
    protected $description = 'Tokenize Documents';

    const MIN_COUNT = 60;

    public function handle()
    {
        if ($id = $this->argument('document')) {
            ldd(implode(' ', Document::find($id)->getTokens(true)));
        }

        $this->confirm('Continue? [y|n]') || die();

        $db = DB::connection('nlp');
        $db->table('documents_tokens')->truncate();
    
        // tokenize documents
        foreach (Document::all() as $doc) {
            $doc->getTokens(true);
        }

        $db->insert("
            INSERT INTO documents_tokens (document_id, token) 
                SELECT id, REPLACE(json_array_elements(tokens::json)::text, '\"', '')
                FROM documents
                WHERE tokens IS NOT NULL
        ");

        // remove rate tokens
        $db->delete('
            DELETE FROM documents_tokens WHERE token IN (
                SELECT token FROM documents_tokens GROUP BY token HAVING COUNT(*) < :min
            )
        ', [
            'min' => self::MIN_COUNT
        ]);
    
        // replace tokens
        $db->update('
            UPDATE documents 
            SET    tokens = t1.json::jsonb
            FROM   (
                SELECT document_id, array_to_json(ARRAY_AGG(token)) AS json
                FROM documents_tokens GROUP BY document_id
            ) AS t1
            WHERE  id = t1.document_id
        ');
    }
}
