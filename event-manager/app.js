class ParentManager extends EventTarget {
    constructor() {
        super();
        this.children = [];
    }
  
    atribuirClasseParaSerOuvida(child) {
        this.children.push(child);
        child.addEventListener('eventoOuvidoDisparado', (event) => {
            this.funcaoParaResponderAoEventoOuvido(event);
        });
        child.addEventListener('outroEventoOuvidoDisparado', (event) => {
            this.funcaoParaResponderAoEventoOuvido(event);
        });
    }
  
    funcaoParaResponderAoEventoOuvido(event) {
        console.log(`O tipo de evento: "${event.type}",`, `Os detalhes: "${event.detail}",`, "A classe: ", event.target);
    }
}
  
class ChildComponent extends EventTarget {
    constructor(elementId) {
      super();
      this.elementId = elementId;
      this.atribuirOuvinteDeClique();
    }
    
    atribuirOuvinteDeClique() {
      const element = document.getElementById(this.elementId);
      if (element) {
            element.addEventListener('click', () => {
                this.dispatchEvent(new CustomEvent('eventoOuvidoDisparado', { detail: "Oii, o botão 1 foi clicado \\(^-^)/" }))
            })
        }
    }
}

class Row extends EventTarget {
    constructor(elementId) {
      super();
      this.elementId = elementId;
      this.atribuirOuvinteDeClique();
    }
  
    atribuirOuvinteDeClique() {
      const element = document.getElementById(this.elementId);
      if (element) {
            element.addEventListener('click', () => {
                // dispathEvent com new CustomEvent => Irá Despachar o Evento "eventoOuvidoDisparado" para o Ouvinte, e atribuir detalhes ao evento. 
                this.dispatchEvent(new CustomEvent('outroEventoOuvidoDisparado', { detail: "Oii, o botão 2 foi clicado \\(^-^)/" }))
            })
        }
    }
}

// Cria uma instância do gerenciador de pai
const parentManager = new ParentManager();

// Cria instâncias de componentes filhos
const child1 = new ChildComponent('btn1');
const child2 = new Row('btn2');

// Adiciona os componentes filhos ao gerenciador de pai
parentManager.atribuirClasseParaSerOuvida(child1);
parentManager.atribuirClasseParaSerOuvida(child2);