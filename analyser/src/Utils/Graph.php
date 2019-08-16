<?php

namespace App\Utils;

use stdClass;

class Graph
{

    protected $items = [];

    protected $statisticsArray = [];

    protected $userText = '';


    public function __construct($userText)
    {
        $this->userText = $userText;
    }

    public function appendNode($node)
    {
        $this->items[$node->id] = $node;
        return $this;
    }


    public function createNode($data)
    {
        $node = new StdClass;

        $node->id = $data['id'];
        $node->value = $data['value'];
        $node->previous = $data['previous'];
        $node->next = $data['next'];;

        return $node;
    }


    public function getNode($id)
    {
        return ($this->items[$id]) ? $this->items[$id] : null;
    }


    public function prepareData()
    {
        $prevNodeId = null;
        $messageArray = str_split($this->userText);

        $messageArray = array_filter($messageArray, function($item) {
            return trim($item) !== '';
        });


        foreach ($messageArray as $index => $letter) {

            $data = [
                'id' => $index,
                'value' => $letter,
                'previous' => $prevNodeId,
                'next' => null,
            ];

            $node = $this->createNode($data);

            if ($node) {
                $this->appendNode($node);
            }

            if (null !== $prevNodeId  && !empty($this->getNode($prevNodeId))) {
                $prevNode = $this->getNode($prevNodeId);
                $prevNode->next = $index;
                $this->appendNode($prevNode);
            }

            $prevNodeId = $index;
        }

        $this->generate();

        return $this;
    }


    private function generate()
    {
        foreach ($this->items as $index => $item) {

            if (!isset($this->statisticsArray[$item->value])) {

                $this->statisticsArray[$item->value] = [
                    'total'          => 1,
                    'distance_start' => $index,
                    'distance_end'   => $index,
                    'before'         => null !== $item->next ? [$this->getNode($item->next)->value] : [],
                    'after'          => null !== $item->previous ? [$this->getNode($item->previous)->value] : []
                ];
                continue;
            }

            $statistics = $this->statisticsArray[$item->value];
            $beforeNodeValue = null !== $item->next ? [$this->getNode($item->next)->value] : [];
            $afterNodeValue  = null !== $item->previous ? [$this->getNode($item->previous)->value] : [];

            $this->statisticsArray[$item->value] = [
                'total'          => $statistics['total'] + 1,
                'distance_start' => $statistics['distance_end'],
                'distance_end'   => $index,
                'before'         => array_merge($statistics['before'], $beforeNodeValue),
                'after'          => array_merge($statistics['after'], $afterNodeValue)
            ];
        }
        return $this;
    }


    public function generateData()
    {
        $data = [];

        foreach ($this->statisticsArray as $key => $value) {

            $distance = null;

            if ($value['total'] > 1) {
                $distance = 10;
            }

            $data[$key] = [
                'total' => $value['total'],
                'before' => implode(',', array_unique($value['before'])),
                'after' => implode(',', array_unique($value['after'])),
                'distance' => $distance
            ];
        }

        return $data;
    }
}