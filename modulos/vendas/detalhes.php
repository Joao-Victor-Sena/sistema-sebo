<?php
session_start();
require_once '../../config/conexao.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) { header("Location: vendas.php"); exit; }

$stmt = $pdo->prepare("SELECT c.*, cl.NOME FROM COMPRA c JOIN CLIENTE cl ON c.CD_CLIENTE = cl.CD_CLIENTE WHERE c.CD_COMPRA = :id");
$stmt->execute([':id' => $id]);
$venda = $stmt->fetch();

$stmt_itens = $pdo->prepare("
    SELECT cl.QUANTIDADE, cl.VALOR_UNITARIO, l.TITULO 
    FROM COMPRA_LIVRO cl 
    JOIN LIVRO l ON cl.CD_LIVRO = l.CD_LIVRO 
    WHERE cl.CD_COMPRA = :id
");
$stmt_itens->execute([':id' => $id]);
$itens = $stmt_itens->fetchAll();

include '../../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card bg-dark border-secondary shadow">
            <div class="card-header border-secondary d-flex justify-content-between align-items-center">
                <h5 class="text-orange mb-0">Venda #<?= $venda['CD_COMPRA'] ?></h5>
                <span class="badge bg-secondary"><?= date('d/m/Y', strtotime($venda['DATA'])) ?></span>
            </div>
            <div class="card-body">
                <h4 class="text-white"><?= $venda['NOME'] ?></h4>
                <hr class="border-secondary">
                
                <table class="table table-dark table-sm">
                    <thead>
                        <tr class="text-white-50">
                            <th>Livro</th>
                            <th>Qtd</th>
                            <th class="text-end">Unit.</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($itens as $item): 
                            $subtotal = $item['QUANTIDADE'] * $item['VALOR_UNITARIO'];
                        ?>
                        <tr>
                            <td><?= $item['TITULO'] ?></td>
                            <td><?= $item['QUANTIDADE'] ?></td>
                            <td class="text-end">R$ <?= number_format($item['VALOR_UNITARIO'], 2, ',', '.') ?></td>
                            <td class="text-end fw-bold">R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end text-orange fw-bold fs-5">TOTAL</td>
                            <td class="text-end text-success fw-bold fs-5">R$ <?= number_format($venda['TOTAL'], 2, ',', '.') ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="card-footer border-secondary text-end">
                <a href="vendas.php" class="btn btn-outline-light">Voltar</a>
            </div>
        </div>
    </div>
</div>


<?php include '../../includes/footer.php'; ?>