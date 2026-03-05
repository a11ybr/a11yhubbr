import { Link } from "react-router";
import { FileText, UserPlus, Star, Calendar } from "lucide-react";
import { Breadcrumb } from "../components/Breadcrumb";

export function Submit() {
  return (
    <div className="flex-1">
      {/* Header */}
      <section className="bg-primary text-primary-foreground py-12 md:py-16">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <Breadcrumb items={[{ label: "Submeter" }]} />
          <div className="flex items-center gap-4 mb-6">
            <Star className="w-12 h-12" aria-hidden="true" />
            <h1 className="text-4xl md:text-5xl">Submeter</h1>
          </div>
          <p className="text-xl text-primary-foreground/90 max-w-2xl">
            Contribua com a comunidade compartilhando conteúdos ou criando seu perfil profissional.
          </p>
        </div>
      </section>

      {/* Submission Options */}
      <section className="py-12 md:py-20">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {/* Submit Content Block */}
            <article className="bg-card border-2 border-border rounded-lg p-8 hover:border-primary transition-colors focus-within:ring-2 focus-within:ring-primary">
              <div className="bg-primary text-primary-foreground w-16 h-16 rounded-lg flex items-center justify-center mb-6">
                <FileText className="w-8 h-8" aria-hidden="true" />
              </div>
              
              <h2 className="text-2xl md:text-3xl mb-4">Submeter conteúdo</h2>
              
              <p className="text-lg text-muted-foreground mb-8">
                Envie artigos, ferramentas, eventos, cursos ou outros recursos sobre acessibilidade digital.
              </p>
              
              <Link
                to="/submeter/conteudo"
                className="inline-block w-full bg-primary text-primary-foreground px-6 py-3 rounded-lg hover:bg-primary/90 transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 text-center"
              >
                Submeter conteúdo
              </Link>
            </article>

            {/* Submit Event Block */}
            <article className="bg-card border-2 border-border rounded-lg p-8 hover:border-primary transition-colors focus-within:ring-2 focus-within:ring-primary">
              <div className="bg-primary text-primary-foreground w-16 h-16 rounded-lg flex items-center justify-center mb-6">
                <Calendar className="w-8 h-8" aria-hidden="true" />
              </div>
              
              <h2 className="text-2xl md:text-3xl mb-4">Submeter evento</h2>
              
              <p className="text-lg text-muted-foreground mb-8">
                Divulgue workshops, conferências, meetups e outros eventos sobre acessibilidade.
              </p>
              
              <Link
                to="/submeter/eventos"
                className="inline-block w-full bg-primary text-primary-foreground px-6 py-3 rounded-lg hover:bg-primary/90 transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 text-center"
              >
                Submeter evento
              </Link>
            </article>

            {/* Submit Profile Block */}
            <article className="bg-card border-2 border-border rounded-lg p-8 hover:border-primary transition-colors focus-within:ring-2 focus-within:ring-primary">
              <div className="bg-primary text-primary-foreground w-16 h-16 rounded-lg flex items-center justify-center mb-6">
                <UserPlus className="w-8 h-8" aria-hidden="true" />
              </div>
              
              <h2 className="text-2xl md:text-3xl mb-4">Submeter perfil</h2>
              
              <p className="text-lg text-muted-foreground mb-8">Cadastre seu perfil ou organização para fazer parte da comunidade.</p>
              
              <Link
                to="/submeter/perfil"
                className="inline-block w-full bg-primary text-primary-foreground px-6 py-3 rounded-lg hover:bg-primary/90 transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 text-center"
              >
                Criar perfil
              </Link>
            </article>
          </div>

          {/* Additional Information */}
          <div className="mt-16 bg-accent rounded-lg p-8">
            <h2 className="text-2xl mb-4">Como funciona a submissão?</h2>
            <div className="space-y-4 text-muted-foreground">
              <div className="flex gap-4">
                <div className="bg-primary text-primary-foreground w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                  <span className="text-sm">1</span>
                </div>
                <div>
                  <h3 className="text-foreground mb-1">Envie sua contribuição</h3>
                  <p>Preencha o formulário com as informações do conteúdo ou perfil.</p>
                </div>
              </div>
              
              <div className="flex gap-4">
                <div className="bg-primary text-primary-foreground w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                  <span className="text-sm">2</span>
                </div>
                <div>
                  <h3 className="text-foreground mb-1">Revisão editorial</h3>
                  <p>Nossa equipe analisará a submissão para garantir qualidade e relevância.</p>
                </div>
              </div>
              
              <div className="flex gap-4">
                <div className="bg-primary text-primary-foreground w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                  <span className="text-sm">3</span>
                </div>
                <div>
                  <h3 className="text-foreground mb-1">Publicação</h3>
                  <p>Conteúdos aprovados são publicados e compartilhados com a comunidade.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
}

