import { useState } from "react";
import { Link } from "react-router";
import { CheckCircle, FileText } from "lucide-react";
import { Breadcrumb } from "../components/Breadcrumb";

export function SubmitContent() {
  const [submitted, setSubmitted] = useState(false);
  const [formData, setFormData] = useState({
    type: "",
    title: "",
    description: "",
    link: "",
    author: "",
    organization: "",
    email: "",
  });

  const contentTypes = [
    { value: "artigo", label: "Artigo" },
    { value: "comunidade", label: "Comunidade" },
    { value: "curso", label: "Curso" },
    { value: "evento", label: "Evento" },
    { value: "ferramenta", label: "Ferramenta" },
    { value: "multimidia", label: "Multimídia" },
    { value: "site", label: "Site ou sistema" },
  ];

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setSubmitted(true);
  };

  const handleChange = (
    e: React.ChangeEvent<
      HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement
    >,
  ) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
  };

  if (submitted) {
    return (
      <div className="flex-1 flex items-center justify-center py-20">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <div className="bg-green-100 text-green-800 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
            <CheckCircle className="w-12 h-12" aria-hidden="true" />
          </div>
          <h1 className="text-3xl md:text-4xl mb-4">Conteúdo submetido com sucesso!</h1>
          <p className="text-lg text-muted-foreground mb-8">
            Obrigado pela sua contribuição. Nossa equipe editorial irá revisar o conteúdo e entraremos em
            contato em breve.
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <Link
              to="/submeter/conteudo"
              onClick={() => setSubmitted(false)}
              className="inline-block bg-primary text-primary-foreground px-6 py-3 rounded-lg hover:bg-primary/90 transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 text-center"
            >
              Submeter outro conteúdo
            </Link>
            <Link
              to="/"
              className="inline-block bg-transparent border-2 border-primary text-primary px-6 py-3 rounded-lg hover:bg-primary hover:text-primary-foreground transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 text-center"
            >
              Voltar para home
            </Link>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="flex-1">
      <section className="bg-primary text-primary-foreground py-12 md:py-16">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <Breadcrumb
            items={[
              { label: "Submeter", href: "/submeter" },
              { label: "Conteúdo" },
            ]}
          />
          <div className="flex items-center gap-4 mb-6">
            <FileText className="w-12 h-12" aria-hidden="true" />
            <h1 className="text-4xl md:text-5xl">Submeter conteúdo</h1>
          </div>
          <p className="text-xl text-primary-foreground/90 max-w-2xl">
            Envie artigos, tutoriais, projetos open source, recursos, eventos ou outros materiais relevantes.
          </p>
        </div>
      </section>

      <section className="py-12 md:py-16 bg-background">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div className="lg:col-span-2">
              <form onSubmit={handleSubmit} className="space-y-8">
                <div className="border border-border rounded-lg p-6 bg-[#ffffff]">
                  <h2 className="text-2xl mb-6">Informações principais do conteúdo</h2>

                  <div className="mb-6">
                    <label htmlFor="type" className="block mb-2">
                      Tipo de conteúdo <span className="text-destructive" aria-label="obrigatório">*</span>
                    </label>
                    <select
                      id="type"
                      name="type"
                      value={formData.type}
                      onChange={handleChange}
                      required
                      className="w-full px-4 py-3 bg-input-background border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-[#ffffff]"
                      aria-required="true"
                    >
                      <option value="">Selecione o tipo de conteúdo</option>
                      {contentTypes.map((type) => (
                        <option key={type.value} value={type.value}>
                          {type.label}
                        </option>
                      ))}
                    </select>
                  </div>

                  <div className="mb-6">
                    <label htmlFor="title" className="block mb-2">
                      Título do conteúdo <span className="text-destructive" aria-label="obrigatório">*</span>
                    </label>
                    <input
                      type="text"
                      id="title"
                      name="title"
                      value={formData.title}
                      onChange={handleChange}
                      required
                      className="w-full px-4 py-3 bg-input-background border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-[#ffffff]"
                      placeholder="Ex: Guia completo de acessibilidade para desenvolvedores"
                      aria-required="true"
                    />
                  </div>

                  <div className="mb-0">
                    <label htmlFor="description" className="block mb-2">
                      Descrição <span className="text-destructive" aria-label="obrigatório">*</span>
                    </label>
                    <textarea
                      id="description"
                      name="description"
                      value={formData.description}
                      onChange={handleChange}
                      required
                      rows={5}
                      className="w-full px-4 py-3 bg-input-background border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none bg-[#ffffff]"
                      placeholder="Descreva brevemente o conteúdo e sua relevância para a comunidade"
                      aria-required="true"
                    />
                  </div>
                </div>

                <div className="border border-border rounded-lg p-6 bg-[#ffffff]">
                  <h2 className="text-2xl mb-6">Detalhes da informação</h2>

                  <div className="mb-6">
                    <label htmlFor="author" className="block mb-2">
                      Autor <span className="text-destructive" aria-label="obrigatório">*</span>
                    </label>
                    <input
                      type="text"
                      id="author"
                      name="author"
                      value={formData.author}
                      onChange={handleChange}
                      required
                      className="w-full px-4 py-3 bg-input-background border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-[#ffffff]"
                      placeholder="Nome da pessoa autora"
                      aria-required="true"
                    />
                  </div>

                  <div className="mb-6">
                    <label htmlFor="organization" className="block mb-2">
                      Organização
                    </label>
                    <input
                      type="text"
                      id="organization"
                      name="organization"
                      value={formData.organization}
                      onChange={handleChange}
                      className="w-full px-4 py-3 bg-input-background border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-[#ffffff]"
                      placeholder="Empresa, ONG, comunidade ou instituição"
                    />
                  </div>

                  <div className="mb-0">
                    <label htmlFor="link" className="block mb-2">
                      Link do conteúdo <span className="text-destructive" aria-label="obrigatório">*</span>
                    </label>
                    <input
                      type="url"
                      id="link"
                      name="link"
                      value={formData.link}
                      onChange={handleChange}
                      required
                      className="w-full px-4 py-3 bg-input-background border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-[#ffffff]"
                      placeholder="https://"
                      aria-required="true"
                    />
                  </div>
                </div>

                <div className="border border-border rounded-lg p-6 bg-[#ffffff]">
                  <h2 className="text-2xl mb-6">Email de contato</h2>
                  <label htmlFor="email" className="block mb-2">
                    Email <span className="text-destructive" aria-label="obrigatório">*</span>
                  </label>
                  <input
                    type="email"
                    id="email"
                    name="email"
                    value={formData.email}
                    onChange={handleChange}
                    required
                    className="w-full px-4 py-3 bg-input-background border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-[#ffffff]"
                    placeholder="seu@email.com"
                    aria-required="true"
                    aria-describedby="email-description-content"
                  />
                  <p id="email-description-content" className="text-sm text-muted-foreground mt-2">
                    O email será privado e utilizado apenas para que a organização do a11yBR possa entrar em
                    contato com a pessoa que realizou a submissão.
                  </p>
                </div>

                <div className="pt-4">
                  <button
                    type="submit"
                    className="bg-primary text-primary-foreground px-8 py-4 rounded-lg hover:bg-primary/90 transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
                  >
                    Enviar para revisão
                  </button>
                </div>
              </form>
            </div>

            <div className="lg:col-span-1 space-y-6">
              <div className="border border-border rounded-lg p-6">
                <h2 className="mb-4">O que aceitamos</h2>
                <ol className="space-y-2">
                  <li className="flex items-start gap-2">1. Artigos especializados</li>
                  <li className="flex items-start gap-2">2. Canais educativos no YouTube</li>
                  <li className="flex items-start gap-2">3. Comunidades digitais</li>
                  <li className="flex items-start gap-2">4. Cursos e materiais educacionais</li>
                  <li className="flex items-start gap-2">5. Ferramentas técnicas</li>
                  <li className="flex items-start gap-2">6. Eventos</li>
                  <li className="flex items-start gap-2">7. Ferramentas técnicas</li>
                  <li className="flex items-start gap-2">8. Podcasts sobre acessibilidade</li>
                  <li className="flex items-start gap-2">9. Sites e sistemas acessíveis</li>
                </ol>
              </div>

              <div className="bg-primary border text-primary-foreground border-border rounded-lg p-6">
                <h2 className="mb-4">Processo de revisão</h2>
                <ol className="space-y-3">
                  <li className="flex gap-3">1. Submissão recebida</li>
                  <li className="flex gap-3">2. Análise editorial (até 7 dias úteis)</li>
                  <li className="flex gap-3">3. Feedback por e-mail</li>
                  <li className="flex gap-3">4. Publicação após aprovação</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
}
