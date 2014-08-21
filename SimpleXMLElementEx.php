<?php

class SimpleXMLElementEx extends SimpleXMLElement {

    public function addCData($cdataText) {
        $node = dom_import_simplexml($this);
        $no = $node->ownerDocument;
        $node->appendChild($no->createCDATASection($cdataText));
    }

}
