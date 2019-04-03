<?php
 namespace Predis\Command; class ZSetScan extends Command { public function getId() { return 'ZSCAN'; } protected function filterArguments(array $arguments) { if (count($arguments) === 3 && is_array($arguments[2])) { $options = $this->prepareOptions(array_pop($arguments)); $arguments = array_merge($arguments, $options); } return $arguments; } protected function prepareOptions($options) { $options = array_change_key_case($options, CASE_UPPER); $normalized = array(); if (!empty($options['MATCH'])) { $normalized[] = 'MATCH'; $normalized[] = $options['MATCH']; } if (!empty($options['COUNT'])) { $normalized[] = 'COUNT'; $normalized[] = $options['COUNT']; } return $normalized; } public function parseResponse($data) { if (is_array($data)) { $members = $data[1]; $result = array(); for ($i = 0; $i < count($members); ++$i) { $result[$members[$i]] = (float) $members[++$i]; } $data[1] = $result; } return $data; } } 