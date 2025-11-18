<section class="bg-light py-4">
    <div class="container">
        <h1 class="text-center mb-4">Saisir un rapport de visite</h1>

        <?php if (!empty($erreur)) : ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <?php if (!empty($info)) : ?>
            <div class="alert alert-info"><?= htmlspecialchars($info) ?></div>
        <?php endif; ?>

        <?php
        $rapport = $rapport ?? [];
        $lesMedicaments = $lesMedicaments ?? [];
        ?>

        <form method="post" action="index.php?uc=rapportVisite&action=validerSaisie">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge bg-secondary">État : <?= isset($rapport['RAP_ETAT']) ? htmlspecialchars($rapport['RAP_ETAT']) : 'saisi en cours' ?></span>
                <a href="index.php?uc=rapportVisite&action=liste" class="btn btn-outline-secondary btn-sm">Retour à la liste</a>
            </div>

            <div class="mb-3">
                <label class="form-label">Praticien visité :</label>
                <select name="idPraticien" class="form-select" required>
                    <option value="">-- Sélectionner un praticien --</option>
                    <?php foreach ($lesPraticiens as $p) : ?>
                        <option value="<?= $p['PRA_NUM'] ?>"
                            <?= ($rapport['PRA_NUM'] ?? '') == $p['PRA_NUM'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['PRA_NOM']) . ' ' . htmlspecialchars($p['PRA_PRENOM']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Date de visite :</label>
                <input type="date" name="dateVisite" class="form-control"
                       value="<?= htmlspecialchars($rapport['RAP_DATEVISITE'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Motif de visite :</label>
                <input type="text" name="motif" class="form-control"
                       placeholder="Ex : Présentation d’un nouveau médicament"
                       value="<?= htmlspecialchars($rapport['RAP_MOTIF'] ?? '') ?>"
                       <?= (isset($rapport['RAP_ETAT']) && $rapport['RAP_ETAT'] === 'validé') ? 'readonly' : '' ?>>
            </div>

            <div class="mb-3">
                <label class="form-label">Contenu du rapport :</label>
                <textarea name="bilan" class="form-control" rows="6"
                          placeholder="Saisissez le contenu complet du rapport..."
                          <?= (isset($rapport['RAP_ETAT']) && $rapport['RAP_ETAT'] === 'validé') ? 'readonly' : '' ?>><?= htmlspecialchars($rapport['RAP_BILAN'] ?? '') ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Médicament présenté :</label>
                <select name="medicamentPresente" class="form-select"
                    <?= (isset($rapport['RAP_ETAT']) && $rapport['RAP_ETAT'] === 'validé') ? 'disabled' : '' ?>>
                    <option value="">-- Sélectionner un médicament --</option>
                    <?php foreach ($lesMedicaments as $medicament) : ?>
                        <option value="<?= htmlspecialchars($medicament['MED_DEPOTLEGAL']) ?>"
                            <?= ($rapport['MEDICAMENT_PRESENTE'] ?? '') === $medicament['MED_DEPOTLEGAL'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($medicament['MED_NOMCOMMERCIAL']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Médicament prescrit :</label>
                <select name="medicamentPrescrit" class="form-select"
                    <?= (isset($rapport['RAP_ETAT']) && $rapport['RAP_ETAT'] === 'validé') ? 'disabled' : '' ?>>
                    <option value="">-- Sélectionner un médicament --</option>
                    <?php foreach ($lesMedicaments as $medicament) : ?>
                        <option value="<?= htmlspecialchars($medicament['MED_DEPOTLEGAL']) ?>"
                            <?= ($rapport['MEDICAMENT_PRESCRIT'] ?? '') === $medicament['MED_DEPOTLEGAL'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($medicament['MED_NOMCOMMERCIAL']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="saisieDefinitive" id="saisieDefinitive"
                    <?= (isset($rapport['RAP_ETAT']) && $rapport['RAP_ETAT'] === 'validé') ? 'checked disabled' : '' ?>>
                <label class="form-check-label" for="saisieDefinitive">Saisie définitive</label>
            </div>

            <?php if (isset($rapport['RAP_NUM'])) : ?>
                <input type="hidden" name="idRapport" value="<?= htmlspecialchars($rapport['RAP_NUM']) ?>">
            <?php endif; ?>

            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>
</section>
