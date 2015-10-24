<?php

namespace BitWasp\Bitcoin\Transaction;

use BitWasp\Bitcoin\Bitcoin;
use BitWasp\Buffertools\Buffer;
use BitWasp\Bitcoin\Script\Script;
use BitWasp\Bitcoin\Script\ScriptInterface;
use BitWasp\Bitcoin\Serializable;
use BitWasp\Bitcoin\Serializer\Transaction\TransactionInputSerializer;

class TransactionInput extends Serializable implements TransactionInputInterface
{
    /**
     * @var string
     */
    private $txid;

    /**
     * @var string|int
     */
    private $vout;

    /**
     * @var string|int
     */
    private $sequence;

    /**
     * @var ScriptInterface
     */
    private $script;

    /**
     * @param string $txid
     * @param string $vout
     * @param ScriptInterface|Buffer $script
     * @param int $sequence
     */
    public function __construct($txid, $vout, ScriptInterface $script = null, $sequence = self::DEFAULT_SEQUENCE)
    {
        $this->txid = $txid;
        $this->vout = $vout;
        $this->script = $script ?: new Script();
        $this->sequence = $sequence;
    }

    /**
     * @return TransactionInput
     */
    public function __clone()
    {
        $this->script = clone $this->script;
    }

    /**
     * Return the transaction ID buffer
     *
     * @return string
     */
    public function getTransactionId()
    {
        return $this->txid;
    }

    /**
     * @return int
     */
    public function getVout()
    {
        return $this->vout;
    }

    /**
     * @return int
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * Return an initialized script. Checks if already has a script
     * object. If not, returns script from scriptBuf (which can simply
     * be null).
     *
     * @return Script
     */
    public function getScript()
    {
        return $this->script;
    }

    /**
     * Check whether this transaction is a coinbase transaction
     *
     * @return boolean
     */
    public function isCoinbase()
    {
        $math = Bitcoin::getMath();
        return $this->getTransactionId() == '0000000000000000000000000000000000000000000000000000000000000000'
            && $math->cmp($this->getVout(), $math->hexDec('ffffffff')) == 0;
    }

    /**
     * @return bool
     */
    public function isFinal()
    {
        $math = Bitcoin::getMath();
        return $math->cmp($this->getSequence(), $math->hexDec('ffffffff')) == 0;
    }

    /**
     * @return Buffer
     */
    public function getBuffer()
    {
        $serializer = new TransactionInputSerializer();
        $out = $serializer->serialize($this);
        return $out;
    }
}
