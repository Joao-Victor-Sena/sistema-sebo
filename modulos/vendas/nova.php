<?php
session_start();
require_once '../../config/conexao.php';

$stmt_cli = $pdo->query("SELECT CD_CLIENTE, NOME FROM CLIENTE ORDER BY NOME");
$clientes = $stmt_cli->fetchAll();

$stmt_liv = $pdo->query("SELECT CD_LIVRO, TITULO, PRECO FROM LIVRO ORDER BY TITULO");
$livros = $stmt_liv->fetchAll();

if (isset($_POST['btn_finalizar'])) {
    $cd_cliente = $_POST['cliente'];
    $data_atual = date('Y-m-d');
    $hora_atual = date('H:i:s');
    
    $livros_selecionados = $_POST['livro_id'] ?? [];
    $quantidades = $_POST['qtd'] ?? [];
    
    if (count($livros_selecionados) > 0 && !empty($cd_cliente)) {
        try {
            $pdo->beginTransaction();

            $sql_compra = "INSERT INTO COMPRA (DATA, HORA, CD_CLIENTE, TOTAL) VALUES (:d, :h, :c, 0)";
            $stmt = $pdo->prepare($sql_compra);
            $stmt->execute([':d' => $data_atual, ':h' => $hora_atual, ':c' => $cd_cliente]);
            $id_compra = $pdo->lastInsertId();

            $total_geral = 0;

            $sql_item = "INSERT INTO COMPRA_LIVRO (CD_COMPRA, CD_LIVRO, QUANTIDADE, VALOR_UNITARIO) VALUES (:compra, :livro, :qtd, :valor)";
            $stmt_item = $pdo->prepare($sql_item);

            $stmt_preco = $pdo->prepare("SELECT PRECO FROM LIVRO WHERE CD_LIVRO = :id");

            for ($i = 0; $i < count($livros_selecionados); $i++) {
                $id_livro = $livros_selecionados[$i];
                $qtd = $quantidades[$i];

                if ($id_livro && $qtd > 0) {
                    $stmt_preco->execute([':id' => $id_livro]);
                    $preco_unitario = $stmt_preco->fetchColumn();

                    $stmt_item->execute([
                        ':compra' => $id_compra,
                        ':livro' => $id_livro,
                        ':qtd' => $qtd,
                        ':valor' => $preco_unitario
                    ]);

                    $total_geral += ($preco_unitario * $qtd);
                }
            }

            $pdo->prepare("UPDATE COMPRA SET TOTAL = :total WHERE CD_COMPRA = :id")
                ->execute([':total' => $total_geral, ':id' => $id_compra]);

            $pdo->commit();
            header("Location: vendas.php?msg=cadastrado");
            exit;

        } catch (Exception $e) {
            $pdo->rollBack();
            $erro = "Erro ao processar venda: " . $e->getMessage();
        }
    } else {
        $erro = "Selecione um cliente e pelo menos um livro.";
    }
}

include '../../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-10">
        <h2 class="text-white mb-4"><i class="bi bi-cart-plus text-orange"></i> Nova Venda</h2>
        
        <?php if(isset($erro)) echo "<div class='alert alert-danger'>$erro</div>"; ?>

        <form method="POST" action="">
            <div class="card bg-dark border-secondary p-4 shadow">
                <div class="mb-4">
                    <label class="text-white">Cliente</label>
                    <select name="cliente" class="form-select bg-dark text-white border-secondary" required>
                        <option value="">Selecione...</option>
                        <?php foreach($clientes as $c): ?>
                            <option value="<?= $c['CD_CLIENTE'] ?>"><?= $c['NOME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <h5 class="text-orange border-bottom border-secondary pb-2 mb-3">Itens da Venda</h5>
                
                <div id="lista-livros">
                    <div class="row mb-2 item-linha">
                        <div class="col-md-8">
                            <select name="livro_id[]" class="form-select bg-dark text-white border-secondary mb-2" required>
                                <option value="">Escolha o Livro...</option>
                                <?php foreach($livros as $l): ?>
                                    <option value="<?= $l['CD_LIVRO'] ?>">
                                        <?= $l['TITULO'] ?> (R$ <?= number_format($l['PRECO'], 2, ',', '.') ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="qtd[]" class="form-control" value="1" min="1" placeholder="Qtd">
                        </div>
                        <div class="col-md-1 text-end">
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="button" class="btn btn-outline-light btn-sm" onclick="adicionarLinha()">
                        <i class="bi bi-plus-circle"></i> Adicionar Livro
                    </button>
                </div>

                <div class="d-grid gap-2 mt-5">
                    <button type="submit" name="btn_finalizar" class="btn bg-orange fw-bold btn-lg">Finalizar Venda</button>
                    <a href="vendas.php" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function adicionarLinha() {
    var linha = document.querySelector('.item-linha').cloneNode(true);
    linha.querySelector('select').value = '';
    linha.querySelector('input').value = '1';
    document.getElementById('lista-livros').appendChild(linha);
}
</script>

<?php include '../../includes/footer.php'; ?>