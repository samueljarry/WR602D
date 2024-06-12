describe('Formulaire d\'Inscription', () => {
    it('test 1 - inscription OK', () => {
        cy.visit('http://127.0.0.1:8000/register');

        // Entrer les informations d'inscription
        cy.get('#username').type('newuser@test.fr');
        cy.get('#password').type('t3stNewPassW0rD!');

        // Soumettre le formulaire
        cy.get('button[type="submit"]').click();

        // Vérifier que l'utilisateur est redirigé après inscription
        cy.contains('Bienvenue').should('exist');
    });

    it('test 2 - inscription KO (email déjà existant)', () => {
        cy.visit('http://127.0.0.1:8000/register');

        // Entrer un email déjà utilisé
        cy.get('#username').type('existinguser@test.fr');
        cy.get('#password').type('t3stPassW0rD!');

        // Soumettre le formulaire
        cy.get('button[type="submit"]').click();

        // Vérifier que le message d'erreur est affiché
        cy.contains('Email already in use.').should('exist');
    });
});
