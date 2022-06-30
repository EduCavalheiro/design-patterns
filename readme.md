# Design Patterns em PHP: padrões comportamentais

# Strategy

> O **Strategy** é um padrão de projeto comportamental que permite que você defina uma família de algoritmos, coloque-os em classes separadas, e faça os objetos deles intercambiáveis. Como diminuir a complexidade do nosso código, trocando múltiplas condicionais por classes.
>

### Problema:

Temos uma calculadora de impostos que recebe um orçamento e nome do imposto, e retorna o valor com o cálculo do imposto solicitado. Inicialmente, usamos *switch case* para decidir qual deve ser o cálculo feito, de acordo com o nome do imposto:
  ```php
  <?php
  
  namespace Alura\DesignPatterns;
  
  class CalculadoraDeImpostos
  {
  
      public function calcula(Orcamento $orcamento, string $nomeImposto): float
      {
          switch ($nomeImposto) {
              case 'ICMS':
                  return $orcamento->valor * 0.1;
              case 'ISS':
                  return $orcamento->valor * 0.06;
          }
      }
  
  }
  ```

### O erro:

Criando nossa classe com essa estrutura, ao ser necessário adicionar outro tipo de imposto, seria necessário alterar a classe *CalculadoraDeImpostos* e adicionar outra condição ao *switch case*. Com o crescimento de necessidades, a implementação precisaria mudar, quebrando o primeiro princípio de SOLID: *SRP — Single Responsibility Principle*.

Além disso, ao enviar uma string que não seja suportada, um erro seria retornado.

### Solução:

Vamos criar uma interface que seja responsável por ditar qual deve ser a implementação básica de um imposto e substituir a *CalculadoraDeImpostos* para que ela passe a receber uma implementação de Imposto, ao invés de uma string.

### Interface

  ```php
  <?php
  
  namespace Alura\DesignPatterns\Impostos;
  
  use Alura\DesignPatterns\Orcamento;
  
  interface Imposto
  {
      public function calculaImposto(Orcamento $orcamento): float;
  }
  ```

### Imposto Icms

  ```php
  <?php
  
  namespace Alura\DesignPatterns\Impostos;
  
  use Alura\DesignPatterns\Orcamento;
  
  class Icms implements Imposto
  {
      public function calculaImposto(Orcamento $orcamento): float
      {
          return $orcamento->valor * 0.01;
      }
  }
  ```

### Imposto Iss

  ```php
  <?php
  
  namespace Alura\DesignPatterns\Impostos;
  
  use Alura\DesignPatterns\Orcamento;
  
  class Iss implements Imposto
  {
  
      public function calculaImposto(Orcamento $orcamento): float
      {
          return $orcamento->valor * 0.06;
      }
  }
  ```

### CalculadoraDeImpostos refatorada

  ```php
  <?php
  
  namespace Alura\DesignPatterns\Impostos;
  
  use Alura\DesignPatterns\Orcamento;
  
  class Iss implements Imposto
  {
  
      public function calculaImposto(Orcamento $orcamento): float
      {
          return $orcamento->valor * 0.06;
      }
  }
  ```

### Conclusão

Ao utilizar o design pattern ***Strategy***, evitamos alterar implementações já existentes quando uma nova necessidade é adicionada, fazendo com que o código se torne sólido (*ba dum tss).*

Evitamos grandes complexidade no nosso código, trocando múltiplas condicionais por classes.

### # Pseudocódigo

  ```php
  // A interface estratégia declara operações comuns a todas as
  // versões suportadas de algum algoritmo. O contexto usa essa
  // interface para chamar o algoritmo definido pelas estratégias
  // concretas.
  interface Imposto is
      method calcularImposto(orcamento)
  
  // Estratégias concretas implementam o algoritmo enquanto seguem
  // a interface estratégia base. A interface faz delas
  // intercomunicáveis no contexto.
  class ImpostoIss implements Strategy is
      method calcularImposto(orcamento) is
          return orcamento->valor * 10
  
  class ImpostoCms implements Strategy is
      method calcularImposto(orcamento) is
          return orcamento->valor * 5
  
  class ImpostoReducao implements Strategy is
      method calcularImposto(imposto) is
          return orcamento->valor - (orcamento->valor * 0.1)
  
  // O contexto define a interface de interesse para clientes.
  class Context is
      // O contexto mantém uma referência para um dos objetos
      // estratégia. O contexto não sabe a classe concreta de uma
      // estratégia. Ele deve trabalhar com todas as estratégias
      // através da interface estratégia.
      private imposto: Imposto
  
      // Geralmente o contexto aceita uma estratégia através do
      // construtor, e também fornece um setter para que a
      // estratégia possa ser trocado durante o tempo de execução.
      method setImposto(Imposto imposto) is
          this.imposto = imposto
  
      // O contexto delega algum trabalho para o objeto estratégia
      // ao invés de implementar múltiplas versões do algoritmo
      // por conta própria.
      method calcular(orcamento) is
          return imposto.calcularImposto(orcamento)
  
  // O código cliente escolhe uma estratégia concreta e passa ela
  // para o contexto. O cliente deve estar ciente das diferenças
  // entre as estratégia para que faça a escolha certa.
  class ExampleApplication is
      method main() is
          Cria um objeto contexto.
  
          Lê o primeiro número.
          Lê o último número.
          Lê a ação desejada da entrada do usuário
  
          if (client.condicao === isentoDeImposto) then
              context.setImposto(new ImpostoReducao())
  
          if (client.condicao === adicioneIss) then
              context.setimposto(new ImpostoIss())
  
          if (client.condicao === adicioneCms) then
              context.setImposto(new ImpostoCms())
  
          result = context.calcular(orcamento)
  
          Imprimir resultado.
  ```
___
# Chain of Responsability

  > O **Chain of Responsibility**
  é um padrão de projeto comportamental que permite que você passe pedidos por uma corrente de handlers. Ao receber um pedido, cada handler decide se processa o pedido ou o passa adiante para o próximo handler na corrente.
  >

  ### Problema:

  Temos uma calculadora de descontos que recebe um orçamento e que de acordo com algumas regras de negócio, aplica um desconto.

    ```php
    <?php
    
    namespace Alura\DesignPatterns;
    
    use Alura\DesignPatterns\Descontos\DescontoMaisCincoItens;
    use Alura\DesignPatterns\Descontos\DescontoMaisQuinhentosReais;
    use Alura\DesignPatterns\Descontos\SemDesconto;
    
    class CalculadoraDeDescontos
    {
        public function calculaDescontos(Orcamento $orcamento): float
        {
            if($orcamento->quantidadeDeItens > 5) {
                        return $orcament->valor * 0.1;
                    }
    
            if($orcamento->valor >= 500) {
                        return $orcament->valor * 0.05;
                    }
    
            return 0;
        }
    }
    ```

  Regra de negócio:

    1. Se a quantidade de itens do orcamento for maior que 5, retorne 10% de desconto;
    2. Se o valor do orçamento for maior ou igual a 500, retorne 5% de desconto;
    3. Se nenhuma regra se aplicar, retorne 0.

  ### O erro:

  Com o script acima, ao ser necessário adicionar um novo desconto, precisaríamos alterar a implementação, algo que pode gerar bugs e alteração de código desnecessariamente.

  ### Solução:

  Vamos criar uma abstract class *Desconto* e adicionar em seu *__construct* o *$proximoDesconto*. Adicionamos também, uma abstract public function que será responsável por fazer o cálculo do desconto.

  Agora, para cada desconto/condição esperada, criamos uma classe dedicada que deve implementar a função responsável por calcular o desconto e que caso a condição necessária para calcular o desconto não seja satisfeita, deve retornar o próximo desconto.

  Após isso, será necessária uma classe responsável por retornar algo, vamos chamá-la de “fim da chain”, caso nenhum dos itens da chain tenha a condição satisfeita.

  Por último, vamos decidir qual deve ser a hierarquia de responsabilidades e instanciar cada classe, sendo que a última classe deve ser a *f”fim da chain”*, a qual não deve receber nada em seu construtor.

  ### Abstract class (Desconto)

    ```php
    <?php
    
    namespace Alura\DesignPatterns\Descontos;
    
    use Alura\DesignPatterns\Orcamento;
    
    abstract class Desconto
    {
        protected ?Desconto $proximoDesconto;
    
        public function __construct(?Desconto $proximoDesconto)
        {
            $this->proximoDesconto = $proximoDesconto;
        }
    
        abstract public function calculaDesconto(Orcamento $orcamento): float;
    
    }
    ```

  ### DescontoMaisDeCincoItens

    ```php
    <?php
    
    namespace Alura\DesignPatterns\Descontos;
    
    use Alura\DesignPatterns\Orcamento;
    
    class DescontoMaisDeCincoItens extends Desconto
    {
        public function calculaDesconto(Orcamento $orcamento): float
        {
            return $orcamento->quantidadeItens > 5
                ? $orcamento->valor * 0.1
                : $this->proximoDesconto->calculaDesconto($orcamento);
        }
    }
    ```

  ### DescontoMaisDeQuinhentosReais

    ```php
    <?php
    
    namespace Alura\DesignPatterns\Descontos;
    
    use Alura\DesignPatterns\Orcamento;
    
    class DescontoMaisDeQuinhentosReais extends Desconto
    {
        public function calculaDesconto(Orcamento $orcamento): float
        {
            return $orcamento->valor >= 100
                ? $orcamento->valor * 0.05
                : $this->proximoDesconto->calculaDesconto($orcamento);
        }
    }
    ```

  ### SemDesconto (*fim da chain)*

    ```php
    <?php
    
    namespace Alura\DesignPatterns\Descontos;
    
    use Alura\DesignPatterns\Orcamento;
    
    class SemDesconto extends Desconto
    {
        public function __construct()
        {
            parent::__construct(null);
        }
    
        public function calculaDesconto(Orcamento $orcamento): float
        {
            return 0;
        }
    }
    ```

  ### CalculadoraDeDescontos

    ```php
    <?php
    
    namespace Alura\DesignPatterns;
    
    use Alura\DesignPatterns\Descontos\DescontoMaisDeCincoItens;
    use Alura\DesignPatterns\Descontos\DescontoMaisDeQuinhentosReais;
    use Alura\DesignPatterns\Descontos\SemDesconto;
    
    class CalculadoraDeDescontos
    {
        public function calculaDescontos(Orcamento $orcamento): float
        {
            $cadeiaDescontos = new DescontoMaisDeCincoItens(
                new DescontoMaisDeQuinhentosReais(
                    new SemDesconto()
                )
            );
    
            return $cadeiaDescontos->calculaDesconto($orcamento);
        }
    }
    ```

  ### Conclusão

  Como muitos outros padrões de projeto comportamental, o Chain of Responsibility se baseia em transformar certos comportamentos em objetos solitários chamados handlers. No nosso caso, cada checagem devem ser extraída para sua própria classe com um único método que faz a checagem. O pedido, junto com seus dados, é passado para esse método como um argumento.

  O padrão sugere que você ligue esses handlers em uma corrente. Cada handler ligado tem um campo para armazenar uma referência ao próximo handler da corrente. Além de processar o pedido, handlers o passam adiante na corrente. O pedido viaja através da corrente até que todos os handlers tiveram uma chance de processá-lo.

  E aqui está a melhor parte: um handler pode decidir não passar o pedido adiante na corrente e efetivamente parar qualquer futuro processamento.

  Em nosso exemplo com sistema de encomendas, um handler realiza o processamento e então decide se passa o pedido adiante na corrente ou não. Assumindo que o pedido contenha os dados adequados, todos os handlers podem executar seu comportamento principal, seja ele uma checagem de autenticação ou armazenamento em cache.

  Contudo, há uma abordagem ligeiramente diferente (e um tanto quanto canônica) na qual, ao receber o pedido, um handler decide se ele pode processá-lo ou não. Se ele pode, ele não passa o pedido adiante. Então é um handler que processa o pedido ou mais ninguém. Essa abordagem é muito comum quando lidando com eventos em pilha de elementos dentro de uma interface gráfica de usuário.