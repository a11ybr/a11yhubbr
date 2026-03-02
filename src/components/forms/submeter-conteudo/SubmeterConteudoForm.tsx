import { useState } from "react";
import { getSupabase } from "@/lib/supabase";
import { FormState } from "./types";

import { BaseFields } from "./BaseFields";
import { ImageFields } from "./ImageFields";
import { SubmitterFields } from "./SubmitterFields";

import { SitesFields } from "./types-specific/SitesFields";
import { MultimidiaFields } from "./types-specific/MultimidiaFields";
import { CursoFields } from "./types-specific/CursoFields";
import { ComunidadeFields } from "./types-specific/ComunidadeFields";
import { EventoFields } from "./types-specific/EventoFields";
import { FerramentaFields } from "./types-specific/FerramentaFields";
import { ArtigoFields } from "./types-specific/ArtigoFields";

const validateByType = (form: FormState): string[] => {
  const errors: string[] = [];

  if (!form.tipo) errors.push("Selecione o tipo de conteúdo.");
  if (!form.titulo.trim()) errors.push("Informe o título.");
  if (!form.url.trim()) errors.push("Informe a URL.");
  if (form.descricao.trim().length < 50)
    errors.push("A descrição deve ter pelo menos 50 caracteres.");
  if (!form.nivel) errors.push("Selecione o nível de profundidade.");
  if (form.publicoAlvo.length === 0)
    errors.push("Selecione pelo menos um público-alvo.");

  switch (form.tipo) {
    case "sites":
      if (!form.modeloNegocio) errors.push("Selecione o modelo de negócio.");
      if (!form.estagioProduto) errors.push("Selecione o estágio do produto.");
      if (!form.modeloAcesso) errors.push("Selecione o modelo de acesso.");
      break;
    case "canal":
      if (!form.tema) errors.push("Informe o tema.");
      if (!form.formato || form.formato.length === 0)
        errors.push("Selecione ao menos um formato.");
      if (!form.plataformas || form.plataformas.length === 0)
        errors.push("Selecione ao menos uma plataforma.");
      break;

    // case "podcasts":
    //   if (!form.tema) errors.push("Informe o tema principal do podcast.");
    //   if (!form.formato) errors.push("Selecione o formato do podcast.");
    //   break;

    case "cursos":
      if (!form.modalidade) errors.push("Selecione a modalidade do curso.");
      if (!form.gratuitoPago) errors.push("Informe se é gratuito ou pago.");
      break;

    case "comunidades":
      if (!form.plataforma) errors.push("Selecione a plataforma.");
      if (!form.acesso) errors.push("Informe o tipo de acesso.");
      break;

    case "eventos":
      if (!form.tipoEvento) errors.push("Selecione o tipo do evento.");
      if (!form.categoriaEvento) errors.push("Selecione a categoria do evento.");
      break;

    case "ferramentas":
      if (!form.tipoFerramenta) errors.push("Selecione o tipo da ferramenta.");
      if (!form.modeloFerramenta) errors.push("Selecione o modelo da ferramenta.");
      break;

    case "artigos":
      if (!form.autoria) errors.push("Informe a autoria.");
      if (!form.tipoArtigo) errors.push("Selecione o tipo de artigo.");
      break;

    // case "youtube":
    //   if (!form.focoCanal) errors.push("Selecione o foco do canal.");
    //   if (!form.tipoConteudoYT)
    //     errors.push("Selecione o tipo de conteúdo do canal.");
    //   break;
  }

  return errors;
};

export function SubmeterConteudoForm() {
  const [form, setForm] = useState<FormState>({
    tipo: "",
    titulo: "",
    url: "",
    descricao: "",
    pais: "",
    ano: "",
    publicoAlvo: [],
    nivel: "",
    imagem: null,
    imagemAlt: "",
    nome: "",
    email: "",
    aceite: false,
  });

  const [loading, setLoading] = useState(false);
  const [submitted, setSubmitted] = useState(false);

  const handleChange = (field: keyof FormState, value: any) => {
    setForm((prev) => ({ ...prev, [field]: value }));
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();

    if (!form.aceite) {
      alert("Você precisa aceitar os termos.");
      return;
    }

    if (form.imagem && !form.imagemAlt) {
      alert("Informe o texto alternativo da imagem.");
      return;
    }

    const errors = validateByType(form);

    if (errors.length > 0) {
      alert(errors.join("\n"));
      return;
    }

    setLoading(true);

    const payload = {
      type: form.tipo,
      title: form.titulo,
      description: form.descricao,
      url: form.url,
      metadata: {
        pais: form.pais,
        ano: form.ano,
        publicoAlvo: form.publicoAlvo,
        nivel: form.nivel,
        modeloNegocio: form.modeloNegocio,
        estagioProduto: form.estagioProduto,
        modeloAcesso: form.modeloAcesso,
        tema: form.tema,
        formato: form.formato,
        formatoOutro: form.formatoOutro,
        modalidade: form.modalidade,
        gratuitoPago: form.gratuitoPago,
        plataforma: form.plataforma,
        acesso: form.acesso,
        tipoEvento: form.tipoEvento,
        categoriaEvento: form.categoriaEvento,
        tipoFerramenta: form.tipoFerramenta,
        modeloFerramenta: form.modeloFerramenta,
        autoria: form.autoria,
        tipoArtigo: form.tipoArtigo,
        focoCanal: form.focoCanal,
        tipoConteudoYT: form.tipoConteudoYT,
      },
      author_name: form.nome,
      author_email: form.email,
      status: "pending",
    };

    const { error } = await getSupabase()
      .from("contents")
      .insert([payload]);

    setLoading(false);

    if (error) {
      console.error(error);
      alert("Erro ao enviar conteúdo.");
      return;
    }

    setSubmitted(true);
  };

  if (submitted) {
    return (
      <div className="py-12 text-center">
        <h2 className="text-2xl font-bold mb-4">
          Submissão recebida
        </h2>
        <p className="text-muted-foreground">
          Sua contribuição será analisada pela equipe editorial.
        </p>
      </div>
    );
  }

  return (
    <form onSubmit={handleSubmit} className="space-y-14">
      <BaseFields form={form} onChange={handleChange} />
      <ImageFields form={form} onChange={handleChange} />

      {form.tipo === "sites" && (
        <SitesFields form={form} onChange={handleChange} />
      )}
      {form.tipo === "cursos" && (
        <CursoFields form={form} onChange={handleChange} />
      )}
      {form.tipo === "comunidades" && (
        <ComunidadeFields form={form} onChange={handleChange} />
      )}
      {form.tipo === "eventos" && (
        <EventoFields form={form} onChange={handleChange} />
      )}
      {form.tipo === "ferramentas" && (
        <FerramentaFields form={form} onChange={handleChange} />
      )}
      {form.tipo === "artigos" && (
        <ArtigoFields form={form} onChange={handleChange} />
      )}
      {form.tipo === "multimidia" && (
        <MultimidiaFields form={form} onChange={handleChange} />
      )}

      <SubmitterFields form={form} onChange={handleChange} />
    </form>
  );
}