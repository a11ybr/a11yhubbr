export type FormState = {
  tipo: string;
  titulo: string;
  url: string;
  descricao: string;

  ano?: number;
  publicoAlvo: string[];
  nivel: string;

  imagem: File | null;
  imagemAlt: string;

  // Multimídia
  tema?: string;
  formato?: string[];
  formatoOutro?: string;
  plataformas?: string[];
  plataformaOutro?: string;
  frequencia?: string;
  transcricao?: boolean;
  legendas?: string;
  recursosAdicionais?: string[];

  // Sites
  modeloNegocio?: string;
  estagioProduto?: string;
  modeloAcesso?: string;

  // Cursos
  modalidade?: string;
  gratuitoPago?: string;

  // Comunidade
  plataforma?: string;
  acesso?: string;

  // Eventos
  tipoEvento?: string;
  categoriaEvento?: string;

  // Ferramentas
  tipoFerramenta?: string;
  modeloFerramenta?: string;

  // Artigos
  autoria?: string;
  tipoArtigo?: string;
  tipoArtigoOutro?: string;

  nome: string;
  email: string;
  aceite: boolean;
};