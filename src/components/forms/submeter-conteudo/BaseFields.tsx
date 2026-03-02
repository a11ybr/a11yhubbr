import { FormState } from "./types";

type Props = {
  form: FormState;
  onChange: (field: keyof FormState, value: any) => void;
}; const contentTypes = [
  {
    value: "artigos",
    title: "Artigos",
    description:
      "Conteúdos escritos com análise, opinião, estudo de caso ou aprofundamento técnico.",
  },
  {
    value: "comunidades",
    title: "Comunidades",
    description:
      "Espaços contínuos de troca, networking e discussão sobre acessibilidade e inclusão.",
  },
  {
    value: "cursos",
    title: "Cursos e Materiais",
    description:
      "Formações estruturadas, trilhas de aprendizado, apostilas ou conteúdos educativos especializados.",
  },
  {
    value: "eventos",
    title: "Eventos",
    description:
      "Iniciativas com data definida como conferências, workshops, meetups ou webinars.",
  },
  {
    value: "ferramentas",
    title: "Ferramentas",
    description:
      "Recursos técnicos para auditoria, testes, design ou apoio à implementação de acessibilidade.",
  },


  {
    value: "multimidia",
    title: "Multimídia",
    description:
      "Canais ou programas distribuídos em áudio ou vídeo, como podcasts e YouTube.",
  },
  {
    value: "sites",
    title: "Sites e Sistemas",
    description:
      "Produtos digitais, plataformas SaaS, sistemas corporativos ou portais com foco em acessibilidade.",
  },



];
export function BaseFields({ form, onChange }: Props) {
  return (
    <div className="space-y-8">
      {/* Tipo */}
      <fieldset className="space-y-2">
        <legend><h2 className="block text-lg font-bold">Tipo de conteúdo</h2></legend>
        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
          {contentTypes.map((type) => {
            const isSelected = form.tipo === type.value;

            return (
              <button
                key={type.value}
                type="button"
                onClick={() => onChange("tipo", type.value)}
                className={`
            text-left p-4 rounded-lg border transition-all
            ${isSelected
                    ? "border-primary bg-primary/10 ring-2 ring-primary"
                    : "border-border hover:border-primary/50"
                  }
          `}
              >
                <div className="font-semibold">
                  {type.title}
                </div>
                <div className="text-xs text-muted-foreground">
                  {type.description}
                </div>
              </button>
            );
          })}
        </div>
      </fieldset>
      <fieldset className="space-y-4">
        <legend><h2 className="block text-lg font-bold">Informações gerais</h2></legend>
        {/* Titulo */}
        <div className="space-y-2">
          <label htmlFor="content-title" className="block text-sm font-medium">
            Título do conteúdo<span aria-hidden="true" className="text-destructive">*</span>
          </label>

          <input
            id="content-title"
            type="text"
            className="input-base"
            placeholder="Insira o título do conteúdo"
            value={form.titulo}
            onChange={(e) => onChange("titulo", e.target.value)}
            required
          />
        </div>

        {/* URL */}
        <div className="space-y-2">
          <label htmlFor="content-url" className="block text-sm font-medium">
            Link do conteúdo<span aria-hidden="true" className="text-destructive">*</span>
          </label>
          <input
            id="content-url"
            type="url"
            className="input-base"
            placeholder="https://..."
            value={form.url}
            onChange={(e) => onChange("url", e.target.value)}
            required
          /><p className="mt-1 text-xs text-muted-foreground">Link para o conteúdo externo, repositório ou página.</p>
        </div>

        {/* Deescrição */}
        <div className="space-y-2">
          <label htmlFor="content-description" className="block text-sm font-medium">
            Descrição<span aria-hidden="true" className="text-destructive">*</span>
          </label>
          <textarea
            id="content-description"
            className="input-base"
            rows={5}
            minLength={50}
            placeholder="Insira uma descrição detalhada do conteúdo, destacando seus pontos fortes e relevância para a comunidade de acessibilidade."
            value={form.descricao}
            onChange={(e) => onChange("descricao", e.target.value)}
            required
          />
          <p className="mt-1 text-xs text-muted-foreground">{form.descricao.length}/50 caracteres mínimos</p>

        </div>
        {/* Data */}
        <div className="space-y-2">
          <label htmlFor="content-year" className="block text-sm font-medium">
            Ano de publicação/Atualização<span aria-hidden="true" className="text-destructive">*</span>
          </label>
          <input
            id="content-year"
            type="number"
            inputMode="numeric"
            min="1900"
            max={new Date().getFullYear()}
            pattern="\d{4}"
            maxLength={4}
            className="input-base"
            placeholder="Ex: 2024"
            value={form.ano || ""}
            onChange={(e) => {
              const value = e.target.value;

              if (value.length <= 4) {
                onChange("ano", value ? Number(value) : undefined);
              }
            }}
            required
          />

        </div>

        {/* Profundidade */}
        <div className="space-y-2">
          <span className="block text-sm font-medium">
            Nível de profundidade
            <span aria-hidden="true" className="text-destructive">*</span>
          </span>

          <div className="grid grid-cols-1 sm:grid-cols-3 gap-3">
            {[
              {
                value: "intro",
                title: "Introdutório",
                description: "Conceitos básicos e visão geral",
              },
              {
                value: "intermediario",
                title: "Intermediário",
                description: "Aplicação prática e aprofundamento",
              },
              {
                value: "avancado",
                title: "Avançado",
                description: "Discussões técnicas e estratégicas",
              },
            ].map((option) => {
              const isSelected = form.nivel === option.value;

              return (
                <label
                  key={option.value}
                  className={`
            cursor-pointer rounded-lg border p-4 transition-all
            ${isSelected
                      ? "border-primary bg-primary/10 ring-2 ring-primary"
                      : "border-border hover:border-primary/50"
                    }
          `}
                >
                  <input
                    type="radio"
                    name="nivel"
                    value={option.value}
                    checked={isSelected}
                    onChange={(e) => onChange("nivel", e.target.value)}
                    className="sr-only"
                    required
                  />

                  <div className="font-semibold">
                    {option.title}
                  </div>

                  <div className="text-xs text-muted-foreground">
                    {option.description}
                  </div>
                </label>
              );
            })}
          </div>
        </div>
      </fieldset>
    </div >
  );
}