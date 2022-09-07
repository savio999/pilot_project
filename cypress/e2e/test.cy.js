describe('test', () => {
  beforeEach(() => {
    cy.visit('/test')
    cy.get("form[data-testid='personal_data']").as('form_submit')
  });

  it('submitting empty form',function() {
    cy.get("@form_submit").submit()
    .then(($myElement) => {
      cy.get("[data-testid='personal_data_first_name']")
      .should('have.class','is-invalid')

      cy.get("[data-testid='personal_data_gender']")
      .find(':radio')
      .should('have.class','is-invalid')

      cy.get("[data-testid='personal_data_work_environment']")
      .should('have.class','is-invalid')

      cy.get("[data-testid='pdf_file']")
      .should('have.class','is-invalid')
    })
   
  })

  it('submitting wrong doc', function() {
    cy.get("[data-testid='pdf_file']").selectFile("public/files/test.txt")    
    cy.get("@form_submit").submit()
    .then(('$myElement'),()=>{
      cy.get("[data-testid='pdf_file']").should('have.class','is-invalid')
    })
  })

  it('form filling', () => {
   // cy.visit('/test',{timeout:10})
    cy.get("[data-testid='personal_data_first_name']").clear().type('test')
    cy.get("[data-testid='personal_data_gender']").find(':radio').check('male')
    cy.get("[data-testid='personal_data_hobbies']").find(':checkbox').uncheck().check('sports')
    cy.get("[data-testid='personal_data_hobbies']").find(':checkbox').check('travel')
    cy.get("[data-testid='personal_data_work_environment']").select('hybrid')
    cy.get("[data-testid='pdf_file']").selectFile("public/files/sample.pdf")    
    cy.get("@form_submit").submit()
    .then(()=>{
      cy.get('div.alert')
      .should('have.class','alert-success')

      //cy.get('div.alert').should('include.text', 'Form sent successfully')
      cy.get('div.alert').contains('Form sent successfully')

    })
  })

  /*it('check no', () => {
    const checkAndReload = () => {
      let  no = Math.floor(Math.random() * 10);
      // get the element's text, convert into a number
      cy.get('#result')
        .type(no)
        .invoke('val')        
        .then(parseInt)
        .then((number) => {
          // if the expected number is found
          // stop adding any more commands
          console.log(number);
          if (number === 7) {
            cy.log('lucky **7**')
    
            return
          }
    
          // otherwise insert more Cypress commands
          // by calling the function after reload
          cy.wait(500, { log: false })
          cy.reload()
          checkAndReload()
        })
    }
    
    cy.visit('/test')
    checkAndReload()
  })*/

  it("select hobbies",()=>{
    cy.get("form[data-testid='personal_data']")
    cy.get("[data-testid='personal_data_hobbies']")
    .find("[type='checkbox']:first")
    .check();
  })

  // it('check random number',()=>{
  //  /* cy.get("[data-testid='result']")
  //   .invoke('val')
  //   .then(parseFloat)
  //   .should('be.gte', 1)
  //   .should('be.lte', 10)*/

  //   cy.get("[data-testid='result']").should(($input) =>{
  //     const no = parseFloat($input.val());

  //     expect(no).to.gte(1).and.to.lte(10)
  //   })
  // })

  it('test double click',()=>{
    cy.get("[data-testid='double_click_btn']")
    .trigger('dblclick')
    .then(($input) => {
      cy.get("[data-testid='double_para']").should('be.visible')
    })
  })

  it('check double btn text', () => {
    cy.get("[data-testid='double_click_btn']").should(($btn) => {
      expect($btn.text()).to.be.eq("Double click me")
    })
  })  
})

