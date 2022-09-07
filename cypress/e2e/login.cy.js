describe('login', () => {
  it('invalid credentials', () => {
    cy.visit('/register')
    cy.get('form#login_form').submit()
    cy.get('div.alert-danger').should('exist')
  })

  it('successful login', () => {
    cy.visit('/register')
    cy.get("#loginUsername").type('test789')
    cy.get("#loginPassword").type('123456{enter}')
    cy.url().should('include','/list')
  })
})