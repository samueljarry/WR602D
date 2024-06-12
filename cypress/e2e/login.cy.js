describe('Formulaire de Connexion', () => {
    it('test 1 - connexion OK', () => {
        cy.visit('http://127.0.0.1:8000/login');

        // Entrer le nom d'utilisateur et le mot de passe
        cy.get('#username').type('test@test.fr');
        cy.get('#password').type('t3stPassW0rD!');

        // Soumettre le formulaire
        cy.get('button[type="submit"]').click();

        // Vérifier que l'utilisateur est connecté
        cy.contains('PDFs').should('exist');
    });

    it('test 2 - connexion KO', () => {
        cy.visit('http://127.0.0.1:8000/login');

        // Entrer un nom d'utilisateur et un mot de passe incorrects
        cy.get('#username').type('test@test.fr');
        cy.get('#password').type('testPassW0rD!');

        // Soumettre le formulaire
        cy.get('button[type="submit"]').click();

        // Vérifier que le message d'erreur est affiché
        cy.contains('Invalid credentials.').should('exist');
    });
});