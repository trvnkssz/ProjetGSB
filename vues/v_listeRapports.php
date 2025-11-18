<section class="bg-light py-4">
    <div class="container">
        <h2 class="mb-4 text-center">Mes rapports de visite</h2>

        <?php if (!empty($erreur)) : ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <?php if (empty($rapports)) : ?>
            <div class="alert alert-info text-center">Aucun rapport trouvé.</div>
        <?php else : ?>
            <table class="table table-bordered table-striped text-center align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>Date</th>
                        <th>Praticien</th>
                        <th>Motif</th>
                        <th>État</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rapports as $r) : ?>
                        <tr class="<?= ($r['RAP_ETAT'] === 'validé') ? 'table-success' : '' ?>">
                            <td><?= htmlspecialchars($r['RAP_DATEVISITE']) ?></td>
                            <td><?= htmlspecialchars($r['PRA_NOM'] . ' ' . $r['PRA_PRENOM']) ?></td>
                            <td><?= htmlspecialchars($r['RAP_MOTIF']) ?></td>
                            <td><strong><?= htmlspecialchars($r['RAP_ETAT']) ?></strong></td>
                            <td>
                                <a href="index.php?uc=rapportVisite&action=consulter&idRapport=<?= $r['RAP_NUM'] ?>" class="btn btn-info btn-sm">Consulter</a>
                                <?php if ($r['RAP_ETAT'] !== 'validé') : ?>
                                    <a href="index.php?uc=rapportVisite&action=modifier&idRapport=<?= $r['RAP_NUM'] ?>" class="btn btn-warning btn-sm">Modifier</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="index.php?uc=rapportVisite&action=nouveau" class="btn btn-primary">
                + Créer un nouveau rapport
            </a>
        </div>
    </div>
</section>
