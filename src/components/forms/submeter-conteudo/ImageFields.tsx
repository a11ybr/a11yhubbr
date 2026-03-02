import { FormState } from "./types";
import { Upload } from "lucide-react";

type Props = {
  form: FormState;
  onChange: (field: keyof FormState, value: any) => void;
};

export function ImageFields({ form, onChange }: Props) {
  return (


    <fieldset className="space-y-4">
      <legend className="block text-lg font-medium">Foto, imagem ou logotipo</legend>
      <label className="flex flex-col items-center justify-center border-2 border-dashed border-border rounded-lg p-6 text-center cursor-pointer hover:border-primary/50 transition">
        <Upload className="mb-3 h-6 w-6 text-muted-foreground" />
        <span className="text-sm font-medium">
          {form.imagem ? form.imagem.name : "Clique para selecionar uma imagem"}
        </span>
        <span className="text-xs text-muted-foreground mt-1">
          ou arraste o arquivo até aqui
        </span>
        <p className="mt-4 text-xs text-muted-foreground text-left">
          Envie uma imagem representativa do conteúdo (ex: capa do curso, screenshot da ferramenta, etc).
        </p>
        <p className="mt-4 text-xs text-muted-foreground text-left"> Aceitamos: JPG, PNG ou WebP. Tamanho máximo: 2MB. Proporção recomendada: 1:1 e tamanho mínimo: 400x400px. Caso nenhuma imagem seja enviada, será gerado um avatar automático com as iniciais do conteúdo.
        </p>
        <input
          type="file"
          accept="image/png,image/jpeg,image/webp"
          className="hidden"
          onChange={(e) =>
            onChange("imagem", e.target.files?.[0] || null)
          }
        />

      </label>

      {form.imagem && (
        <div className="pt-4 space-y-2">
          <label className="block text-sm font-medium">
            Texto alternativo da imagem (alt)
            <span aria-hidden="true" className="text-destructive">*</span>
          </label>

          <input
            type="text"
            className="input-base"
            placeholder="Descreva a imagem para leitores de tela"
            value={form.imagemAlt}
            onChange={(e) => onChange("imagemAlt", e.target.value)}
            required
          />

          <p className="text-xs text-muted-foreground">
            O texto alternativo é obrigatório para garantir acessibilidade.
          </p>
        </div>
      )
      }
    </fieldset>);
}