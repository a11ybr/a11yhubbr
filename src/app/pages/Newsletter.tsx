import { useState } from "react";
import { Mail, CheckCircle, BookOpen, Calendar, Target} from "lucide-react";
import { Breadcrumb } from "../components/Breadcrumb";

export function Newsletter() {
  const [email, setEmail] = useState("");
  const [subscribed, setSubscribed] = useState(false);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    // Simulate subscription
    setSubscribed(true);
  };

  if (subscribed) {
    return (
      <div className="flex-1 flex items-center justify-center py-20">
        <div className="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <div className="bg-green-100 text-green-800 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
            <CheckCircle className="w-12 h-12" aria-hidden="true" />
          </div>
          <h1 className="text-3xl md:text-4xl mb-4">Inscrição confirmada!</h1>
          <p className="text-lg text-muted-foreground mb-8">
            Obrigado por se inscrever na newsletter do a11yBR. Você receberá em breve as últimas novidades sobre acessibilidade digital no Brasil.
          </p>
          <a
            href="/"
            className="inline-block bg-primary text-primary-foreground px-6 py-3 rounded-lg hover:bg-primary/90 transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
          >
            Voltar para home
          </a>
        </div>
      </div>
    );
  }

  return (
    <div className="flex-1">
      {/* Header */}
      <section className="bg-primary text-primary-foreground py-12 md:py-16">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <Breadcrumb items={[{ label: "Newsletter" }]} />
          <div className="flex items-center gap-4 mb-6">
            <Mail className="w-12 h-12" aria-hidden="true" />
            <h1 className="text-4xl md:text-5xl">Newsletter</h1>
          </div>
          <p className="text-xl text-primary-foreground/90 max-w-2xl">
            Receba as últimas novidades, recursos e discussões sobre acessibilidade digital diretamente no seu e-mail.
          </p>
        </div>
      </section>

      {/* Newsletter Form */}
      <section className="py-16 md:py-20">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="bg-card border border-border rounded-lg p-8 md:p-12">
            <h2 className="text-2xl md:text-3xl mb-4">Inscreva-se gratuitamente</h2>
            <p className="text-lg text-muted-foreground mb-8">
              Enviamos conteúdos selecionados semanalmente para ajudar você a se manter atualizado sobre acessibilidade digital.
            </p>

            <form onSubmit={handleSubmit} className="mb-8">
              <div className="mb-4">
                <label htmlFor="email" className="block mb-2">
                  Endereço de e-mail
                </label>
                <input
                  type="email"
                  id="email"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  required
                  className="w-full px-4 py-3 bg-input-background border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                  placeholder="seu@email.com"
                  aria-required="true"
                />
              </div>

              <button
                type="submit"
                className="w-full bg-primary text-primary-foreground px-8 py-4 rounded-lg hover:bg-primary/90 transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
              >
                Inscrever na newsletter
              </button>
            </form>

            <p className="text-sm text-muted-foreground text-center">
              Respeitamos sua privacidade. Você pode cancelar a inscrição a qualquer momento.
            </p>
          </div>

          {/* Benefits */}
          <div className="mt-16">
            <h2 className="text-2xl md:text-3xl mb-8 text-center">O que você vai receber</h2>
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
              <article className="text-center">
                <div className="bg-accent rounded-lg p-6 mb-4">
                  <BookOpen className="mx-auto w-12 h-12" aria-hidden="true" />
                  <h3 className="text-lg mx-[0px] mt-[32px] mb-[8px]">Conteúdos selecionados</h3>
                  <p className="text-muted-foreground">
                    Artigos, ferramentas e recursos cuidadosamente escolhidos
                  </p>
                </div>
              </article>

              <article className="text-center">
                <div className="bg-accent rounded-lg p-6 mb-4">
                  <Calendar className="mx-auto w-12 h-12" aria-hidden="true" />
                  <h3 className="text-lg mx-[0px] mt-[32px] mb-[8px]">Calendário de eventos</h3>
                  <p className="text-muted-foreground">
                    Fique por dentro de conferências, workshops e webinars
                  </p>
                </div>
              </article>

              <article className="text-center">
                <div className="bg-accent rounded-lg p-6 mb-4">
                  <Target className="mx-auto w-12 h-12" aria-hidden="true" />
                  <h3 className="text-lg mx-[0px] mt-[32px] mb-[8px]">Dicas práticas</h3>
                  <p className="text-muted-foreground">
                    Sugestões para melhorar a acessibilidade dos seus projetos
                  </p>
                </div>
              </article>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
}
