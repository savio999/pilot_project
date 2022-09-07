describe('test', () => {
    beforeEach(() => {
      cy.visit('/test')
      cy.get("[data-testid='result']").invoke('val').as('txt')
    });

    it('check if number is incrementing correctly', function(){
        // cy.get("[data-testid='result']").then(($input) => {
        //     const num = parseFloat($input.val())
            
        //     cy.get("[data-testid='click_me_btn']")
        //     .trigger('click')
        //     .then(($btn) => {
        //        const num2 = parseFloat($input.val())
        //        expect(num2).to.eq(num + 1)
        //     })
        // })

        cy.get("[data-testid='click_me_btn']")
        .trigger('click')
        .then(($btn) =>{
            cy.get("[data-testid='result']").then(($input) =>{
                const num4 = parseFloat($input.val())
                const num5 = parseFloat(this.txt)
                expect(num4).to.eq(num5 + 1)
            })
            
        })
       
    });

    it("conditional testing",() => {
        cy.get("[data-testid='another_btn']").click().then(($btn)=>{
            cy.get("body").then($body => {
                if($body.find("input.test_element").length){
                    return "input.test_element"
                }else{
                    return "textarea.test_element"
                }
            }).then(function($selector){
                cy.get($selector).type($selector + " is there", { force: true })
            })
        })
    })
});