import {
  Globe,
  Headphones,
  BookOpen,
  Users,
  Calendar,
  Wrench,
  FileText,
  Video
} from "lucide-react";
import type { LucideIcon } from "lucide-react";

export type ContentType = {
  id: string;
  title: string;
  description: string;
  icon: LucideIcon;
};

export const CONTENT_TYPES: readonly ContentType[] = [
  {
    id: "artigos",
    title: "Artigos",
    description:
      "Conteúdos escritos com análise, opinião, estudo de caso ou aprofundamento técnico.",
    icon: FileText
  },
  {
    id: "comunidades",
    title: "Comunidades",
    description:
      "Espaços contínuos de troca, networking e discussão sobre acessibilidade e inclusão.",
    icon: Users
  },
  {
    id: "cursos",
    title: "Cursos e Materiais",
    description:
      "Formações estruturadas, trilhas de aprendizado, apostilas ou conteúdos educativos especializados.",
    icon: BookOpen
  },
  {
    id: "eventos",
    title: "Eventos",
    description:
      "Iniciativas com data definida como conferências, workshops, meetups ou webinars.",
    icon: Calendar
  },
  {
    id: "ferramentas",
    title: "Ferramentas",
    description:
      "Recursos técnicos para auditoria, testes, design ou apoio à implementação de acessibilidade.",
    icon: Wrench
  },
  {
    id: "multimidia",
    title: "Multimídia",
    description:
      "Canais ou programas distribuídos em áudio ou vídeo, como podcasts e YouTube.",
    icon: Headphones
  },
  {
    id: "sites",
    title: "Sites e Sistemas",
    description:
      "Produtos digitais, plataformas SaaS, sistemas corporativos ou portais com foco em acessibilidade.",
    icon: Globe
  }
] as const;