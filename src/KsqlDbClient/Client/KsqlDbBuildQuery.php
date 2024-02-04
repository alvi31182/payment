<?php

declare(strict_types=1);

namespace App\KsqlDbClient\Client;

use Doctrine\ORM\Query\AST\Node;
use InvalidArgumentException;
use PhpParser\Error;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeDumper;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;

class KsqlDbBuildQuery
{
    public function selectStream(string $ksqlDbQuery, array $arguments = []): string
    {
        if (!empty($arguments)) {
        }
        return $ksqlDbQuery;
    }

    public function createStream(string $ksqlDbQuery, array $arguments = []): string
    {
        $code = <<<'CODE'
<?php

function test($foo)
{
    var_dump($foo);
}
CODE;
        $dumper = new NodeDumper;
        $parser = (new ParserFactory())->createForNewestSupportedVersion();
        try {
            //$ast = $parser->parse($code);
            $traverser = new NodeTraverser();
            $traverser->addVisitor(new class extends NodeVisitorAbstract {
                public function enterNode(Node|\PhpParser\Node $node) {
                    if ($node instanceof Function_) {
                        // Clean out the function body
                        $node->stmts = [];
                    }
                }
            });

//            $ast = $traverser->traverse($ast);
            echo $dumper->dump($ast) . "\n";
        } catch (Error $error) {
            echo "Parse error: {$error->getMessage()}\n";
            return '';
        }


        echo $dumper->dump($ast) . "\n";

//        $countMatch = preg_match_all('/(\?WS|\?S|\?AS|\?WIN)/', $ksqlDbQuery, $matches);
//
//        if (!$this->hasNestedArray(sqlArguments: $arguments)) {
//            $results = [];
//            foreach ($matches[0] as $argumentValue) {
//                switch ($argumentValue) {
//                    case '?WS':
//                        $ksqlDbQuery = $this->stringQueryWhereList(query: $ksqlDbQuery, queryArguments: $arguments);
//                        break;
//                    case '?AS':
//                        $ksqlDbQuery = $this->stringAndOperatorQuery(query: $ksqlDbQuery, queryArguments: $arguments);
//                }
//            }
//            dd($ksqlDbQuery);
//        }
//
//
////        if ($countMatch > 0){
////            $keys = array_keys($matches);
////            if (!empty($matches) && $keys && isset($matches[0])){
////
////                $values = array_values($matches[0]);
////
////                foreach ($values as $value){
////                    switch ($value){
////                        case '?S':
////                            $this->stringQueryList(queryArguments: $arguments);
////                            break;
////                        case '?WS':
////                            $this->stringQueryWhereList(queryArguments: $arguments);
////                            break;
////                        case '?AS':
////                            $this->stringAndOperatorQuery(queryArguments: $arguments);
////                            break;
////                        case '?WI':
////                            $this->intWhereInQuery(queryArguments: $arguments);
////                            break;
////                    }
////                }
////            }
////        }
//
//
////        if (!str_contains($ksqlDbQuery, 'CREATE')) {
////            throw new InvalidArgumentException('The query must contain the "CREATE" keyword or "CREATE TABLE" OR "CREATE VIEWS"');
////        }
////
////       // dd($arguments);
////
////        if (!empty($arguments)) {
////            if (array_key_exists(0, $arguments)) {
////                $ksqlDbQuery = str_replace('?S', implode(', ', $arguments[0] ?? []), $ksqlDbQuery);
////            }
////            foreach ($arguments as $key => $value){
////                if (is_int($key)){
////                    $ksqlDbQuery = preg_replace_callback('/\?WS/', function ($matches) use ($arguments) {
////                        $replacement = $arguments[1] ?? null;
////                        return is_string($replacement) ? "'" . $replacement . "'" : '';
////                    }, $ksqlDbQuery);
////                }
////            }
////        }
//
//        return $ksqlDbQuery;
    }

    private function stringQueryList(array $queryArguments)
    {
        dd($queryArguments);
        // return $queryArguments;
    }

    private function stringQueryWhereList(string $query, array $queryArguments): array|string|null
    {
        foreach ($queryArguments as $key => $value) {

            if (is_int($key)) {
                $query = preg_replace_callback('/\?WS/', function ($matches) use ($queryArguments) {
                    $replacement = implode(',', $queryArguments) ?? null;
                    return is_string($replacement) ? "(" . "'" .$replacement."'" . ")" : '';
                }, $query);
            }
        }

        return $query;
    }

    private function stringAndOperatorQuery(string $query, array $queryArguments): array|string|null
    {
        foreach ($queryArguments as $key => $value) {

            if (is_int($key)) {
                $query = preg_replace_callback('/\?AS/', function ($matches) use ($queryArguments) {
                    $replacement = implode(',', $queryArguments) ?? null;
                    return is_string($replacement) ? "(" . "'" .$replacement."'" . ")" : '';
                }, $query);
            }
        }

        return $query;
    }

    private function intWhereInQuery(array $queryArguments)
    {
        dd('rr');
    }

    public function hasNestedArray(array $sqlArguments): bool
    {
        foreach ($sqlArguments as $argument) {
            if (is_array($argument)) {
                return true;
            }
        }

        return false;
    }
}