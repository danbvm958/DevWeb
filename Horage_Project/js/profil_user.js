let edit=document.querySelectorAll(".edit-btn");
let currentEditing=null;

edit.forEach((button)=>{
    button.addEventListener('click',()=>{
        if (currentEditing) {
            currentEditing.input.replaceWith(currentEditing.span);
            currentEditing.container.replaceWith(currentEditing.button);
            currentEditing = null;
        }

        span=button.previousElementSibling;
        field=span.getAttribute("data-field");

        const NewInput=document.createElement('input');
        NewInput.type="text";
        span.replaceWith(NewInput);

        NewInput.addEventListener('keydown', (event) => {
            if (event.key === "Enter") {
                event.preventDefault();
                ValidButton.click(); 
            }
        });

        const ValidButton=document.createElement('button');
        ValidButton.type="button";
        ValidButton.className="edit-btn"
        ValidButton.textContent="✔️";

        const UnvalidButton=document.createElement('button');
        UnvalidButton.type="button";
        UnvalidButton.className="edit-btn"
        UnvalidButton.textContent="✖️";  

        const buttonContainer = document.createElement('div');
        buttonContainer.append(ValidButton, UnvalidButton);

        span.replaceWith(NewInput);
        button.replaceWith(buttonContainer);

        currentEditing = {
            input: NewInput,
            span: span,
            button: button,
            container: buttonContainer
        };

        UnvalidButton.addEventListener('click',()=>{
            NewInput.replaceWith(span);
            buttonContainer.replaceWith(button);
        })
        ValidButton.addEventListener('click',()=>{
            const NewValue=NewInput.value.trim();
            modifUser(NewValue,field).then(resultat =>{
                if (resultat== "Mise à jour réussie."){
                    // console.log("debug");
                    NewInput.replaceWith(span);
                    buttonContainer.replaceWith(button);
                    span.textContent = NewValue;
                }
                else{
                    NewInput.replaceWith(span);
                    buttonContainer.replaceWith(button); 
                }
            })
                

        })
    })

}

)

async function modifUser(Value, field){
    const send = await fetch('modif_user.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `field=${encodeURIComponent(field)}&value=${encodeURIComponent(Value)}`
    })
    const resultat = await send.text();
    // console.log(resultat);
    return resultat;
}
