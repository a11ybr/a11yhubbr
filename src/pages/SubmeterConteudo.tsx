import { BreadcrumbNav } from "@/components/ui/BreadcrumbNav";
import { SubmeterConteudoForm } from "@/components/forms/submeter-conteudo/SubmeterConteudoForm";
import { Button } from "@/components/ui/button";
import { ChevronRight } from "lucide-react";

export default function SubmeterConteudoPage() {
  return (
    <main className="container-site py-16">

      <BreadcrumbNav
        items={[
          { label: "Home", href: "/" },
          { label: "Submeter conteúdo" },
        ]}
      />

      <header className="mt-8 mb-16 reading-width">
        <span className="section-label mb-4 block">
          Comunidade
        </span>

        <h1 className="text-4xl font-bold text-foreground mb-4">
          Submeter conteúdo
        </h1>

        <p className="text-muted-foreground text-lg leading-relaxed">
          Contribua com a comunidade enviando recursos relevantes sobre acessibilidade.
        </p>
        <p className="text-muted-foreground pt-4 text-sm leading-relaxed">
          Campos marcados com <span aria-hidden="true" className="text-destructive">*</span> são obrigatórios.
        </p>
      </header>

      <div className="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-16">

        {/* FORMULÁRIO */}
        <div className="reading-width">
          <SubmeterConteudoForm />
          <div className="pt-8">
            <Button size="lg">
              Enviar conteúdo
              <ChevronRight size={16} className="ml-2" />
            </Button>
          </div>
        </div>


        {/* SIDEBAR */}
        <aside className="reading-width">
          <div className="sticky top-28 space-y-8">

            <div className="card-base p-6 space-y-4">
              <h3 className="font-semibold text-foreground">
                Processo de revisão
              </h3>

              <ol className="space-y-2 text-sm text-muted-foreground">
                <li>1. Submissão recebida</li>
                <li>2. Análise editorial (até 7 dias úteis)</li>
                <li>3. Feedback por e-mail</li>
                <li>4. Publicação após aprovação</li>
              </ol>
            </div>

            <div className="card-base p-6 space-y-4">
              <h3 className="font-semibold text-foreground">
                O que aceitamos
              </h3>

              <ul className="space-y-2 text-sm text-muted-foreground">
                <li>Sites e sistemas acessíveis</li>
                <li>Podcasts sobre acessibilidade</li>
                <li>Cursos e materiais educacionais</li>
                <li>Comunidades digitais</li>
                <li>Eventos</li>
                <li>Ferramentas técnicas</li>
                <li>Artigos especializados</li>
                <li>Canais educativos no YouTube</li>
              </ul>
            </div>

          </div>
        </aside>

      </div>
    </main>
  );
}