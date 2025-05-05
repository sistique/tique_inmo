<?php

namespace gamboamartin\gastos\models;

class Stream
{
    private $elements;
    /** Almacena los elementos de la secuencia. */

    /**
     * Constructor privado.
     *
     * @param array $elements Los elementos de la secuencia.
     */
    private function __construct(array $elements)
    {
        $this->elements = $elements;
    }

    /**
     * Crea una nueva instancia de Stream con los elementos proporcionados.
     *
     * @param array $elements Los elementos para la secuencia.
     * @return Stream Una nueva instancia de Stream.
     *
     * @example $stream = Stream::of([1, 2, 3, 4, 5]);
     * @example $stream = Stream::of(['a', 'b', 'c', 'd', 'e']);
     */
    public static function of(array $elements): Stream
    {
        return new Stream($elements);
    }

    /**
     * Filtra los elementos de la secuencia según un predicado.
     *
     * @param callable $predicate La función de filtro.
     * @return Stream Una nueva instancia de Stream que contiene los elementos filtrados.
     *
     * @example $filtrados = Stream::of([1, 2, 3, 4, 5])->filter(fn($x) => $x % 2 === 0);
     * @example $filtrados = Stream::of(['a', 'b', 'c', 'd', 'e'])->filter(fn($x) => $x !== 'c');
     */
    public function filter(callable $predicate): Stream
    {
        $filtered = array_filter($this->elements, $predicate);
        return new Stream(array_values($filtered));
    }

    /**
     * Aplica una función de mapeo a cada elemento de la secuencia.
     *
     * @param callable $mapper La función de mapeo.
     * @return Stream Una nueva instancia de Stream con los resultados del mapeo.
     *
     * @example $mapeados = Stream::of([1, 2, 3, 4, 5])->map(fn($num) => $num * 2);
     * @example $mapeados = Stream::of(['a', 'b', 'c', 'd', 'e'])->map(fn($letra) => strtoupper($letra));
     */
    public function map(callable $mapper): Stream
    {
        $mapped = array_map($mapper, $this->elements);
        return new Stream($mapped);
    }

    /**
     * Reduce la secuencia a un solo valor aplicando repetidamente una función de reducción.
     *
     * @param callable $reducer La función de reducción.
     * @param mixed $initial (Opcional) El valor inicial para la reducción.
     * @return mixed El resultado de la reducción.
     *
     * @example $suma = Stream::of([1, 2, 3, 4, 5])->reduce(fn($acc, $num) => $acc + $num, 0);
     * @example $concatenados = Stream::of(['a', 'b', 'c', 'd', 'e'])->reduce(fn($acc, $letra) => $acc . $letra, '');
     */
    public function reduce(callable $reducer, mixed $initial = null): mixed
    {
        return array_reduce($this->elements, $reducer, $initial);
    }

    /**
     * Devuelve todos los elementos de la secuencia como un array.
     *
     * @return array Los elementos de la secuencia.
     *
     * @example $array = Stream::of([1, 2, 3, 4, 5])->toArray();
     * @example $array = Stream::of(['a', 'b', 'c', 'd', 'e'])->toArray();
     */
    public function toArray(): array
    {
        return $this->elements;
    }

    /**
     * Aplica una acción a cada elemento de la secuencia.
     *
     * @param callable $action La función de acción.
     * @return void
     *
     * @example  $stream = Stream::of([1, 2, 3, 4, 5])->forEach(fn($num) => echo $num . " ");
     * @example  $stream = Stream::of(['a', 'b', 'c', 'd', 'e'])->forEach(fn($letra) => echo $letra . " ");
     */
    public function forEach(callable $action): void
    {
        foreach ($this->elements as $element) {
            $action($element);
        }
    }

    /**
     * Devuelve el primer elemento de la secuencia.
     *
     * @return mixed El primer elemento de la secuencia o null si está vacía.
     *
     * @example $primero = Stream::of([1, 2, 3, 4, 5])->findFirst();
     * @example $primero = Stream::of(['a', 'b', 'c', 'd', 'e'])->findFirst();
     */
    public function findFirst(): mixed
    {
        return $this->elements[0] ?? null;
    }

    /**
     * Devuelve una nueva instancia de Stream que contiene solo los elementos distintos.
     *
     * @return Stream Una nueva instancia de Stream con elementos distintos.
     *
     * @example $distintos = Stream::of([1, 2, 2, 3, 4, 4, 5])->distinct();
     * @example $distintos = Stream::of(['a', 'b', 'b', 'c', 'd', 'd', 'e'])->distinct();
     */
    public function distinct(): Stream
    {
        $unique = array_unique($this->elements);
        return new Stream(array_values($unique));
    }

    /**
     * Aplica una función de mapeo a cada elemento de la secuencia y aplana los resultados.
     *
     * @param callable $mapper La función de mapeo.
     * @return Stream Una nueva instancia de Stream con los resultados aplastados.
     *
     * @example $mapeados = Stream::of([1, 2, 3, 4, 5])->flatMap(fn($num) => [$num, $num * 2]);
     * @example $mapeados = Stream::of(['a', 'b', 'c', 'd', 'e'])->flatMap(fn($letra) => [$letra, strtoupper($letra)]);
     */
    public function flatMap(callable $mapper): Stream
    {
        $flattened = [];
        foreach ($this->elements as $element) {
            $result = $mapper($element);
            if (is_array($result)) {
                $flattened = array_merge($flattened, $result);
            } else {
                $flattened[] = $result;
            }
        }
        return new Stream($flattened);
    }

}

