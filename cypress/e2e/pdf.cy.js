describe('Générer des PDF', () => {
    it('test 1 - affichage du formulaire', () => {
        cy.visit('http://127.0.0.1:8000/html-to-pdf');

        // Vérifier que le formulaire et le champ URL sont affichés
        cy.get('form').should('exist');
        cy.get('input[name="form[url]"]').should('exist');
    });

    it('test 2 - génération de PDF OK', () => {
        cy.login('validUser', 'validPass'); // Assurez-vous de définir une commande personnalisée pour la connexion

        cy.visit('http://127.0.0.1:8000/html-to-pdf');

        // Entrer l'URL à convertir en PDF
        cy.get('input[name="form[url]"]').type('https://example.com');

        // Soumettre le formulaire
        cy.get('form').submit();

        // Vérifier que le PDF est généré et affiché
        cy.location('pathname').should('include', '/generate-from-url');
        cy.get('embed[type="application/pdf"]').should('exist');
    });

    it('test 3 - génération de PDF KO (limite atteinte)', () => {
        cy.login('userWithMaxPdfs', 'validPass'); // Assurez-vous de définir une commande personnalisée pour la connexion

        cy.visit('http://127.0.0.1:8000/html-to-pdf');

        // Entrer l'URL à convertir en PDF
        cy.get('input[name="form[url]"]').type('https://example.com');

        // Soumettre le formulaire
        cy.get('form').submit();

        // Vérifier que l'utilisateur est redirigé vers la page de mise à niveau de l'abonnement
        cy.location('pathname').should('include', '/upgrade_subscription');
    });

    it('test 4 - génération de PDF KO (URL non fournie)', () => {
        cy.login('validUser', 'validPass'); // Assurez-vous de définir une commande personnalisée pour la connexion

        cy.visit('http://127.0.0.1:8000/html-to-pdf');

        // Soumettre le formulaire sans entrer d'URL
        cy.get('form').submit();

        // Vérifier que le message d'erreur est affiché (cas dépendant de votre validation de formulaire)
        cy.get('.error-message').should('contain', 'This value should not be blank');
    });
});
