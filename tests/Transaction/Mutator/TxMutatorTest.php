<?php

namespace BitWasp\Bitcoin\Tests\Transaction\Mutator;

use BitWasp\Bitcoin\Collection\Transaction\TransactionInputCollection;
use BitWasp\Bitcoin\Collection\Transaction\TransactionOutputCollection;
use BitWasp\Bitcoin\Script\Script;
use BitWasp\Bitcoin\Tests\AbstractTestCase;
use BitWasp\Bitcoin\Transaction\Mutator\TxMutator;
use BitWasp\Bitcoin\Transaction\Transaction;
use BitWasp\Bitcoin\Transaction\TransactionInput;
use BitWasp\Bitcoin\Transaction\TransactionOutput;

class TxMutatorTest extends AbstractTestCase
{
    public function testModifiesTransaction()
    {
        $tx = new Transaction(
            1,
            new TransactionInputCollection(),
            new TransactionOutputCollection(),
            20
        );

        $newVersion = 10;
        $newLockTime = 200;

        $mutator = new TxMutator($tx);
        $mutator
            ->version($newVersion)
            ->locktime($newLockTime)
        ;

        $mutator->inputs(new TransactionInputCollection([
            new TransactionInput('a', 1, new Script())
        ]));

        $mutator->outputs(new TransactionOutputCollection([
            new TransactionOutput(50, new Script())
        ]));

        $newTx = $mutator->get();
        $this->assertEquals($newVersion, $newTx->getVersion());
        $this->assertEquals($newLockTime, $newTx->getLockTime());
        $this->assertEquals(1, count($newTx->getInputs()));
        $this->assertEquals(1, count($newTx->getOutputs()));
    }
}
