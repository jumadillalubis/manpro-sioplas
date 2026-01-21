from fastapi import FastAPI, UploadFile, File, HTTPException
import tempfile
import os
from llama_index.core import VectorStoreIndex, SimpleDirectoryReader, Settings
from llama_index.llms.llama_cpp import LlamaCPP
from llama_index.embeddings.huggingface import HuggingFaceEmbedding
from llama_index.core.node_parser import SentenceSplitter

app = FastAPI(
    title="PDF Summarizer Profesional (Bahasa Indonesia)",
    description="Hasil ringkasan berkualitas tinggi dengan Qwen2.5-3B - CPU only, ringan untuk RAM ≤8GB"
)

# === PATH KE MODEL QWEN 3B ===
MODEL_PATH = "Qwen2.5-3B-Instruct-Q5_K_M.gguf"  # Pastikan file ini ada di folder yang sama

# Load model Qwen2.5-3B (CPU only)
llm = LlamaCPP(
    model_path=MODEL_PATH,
    temperature=0.2,
    max_new_tokens=1400,           # Lebih besar agar ringkasan lengkap & tidak terpotong
    context_window=8192,
    model_kwargs={
        "n_gpu_layers": 0,         # CPU only
        "n_batch": 512,
        "n_ctx": 8192,
    },
    verbose=False,
)

# Embedding multilingual yang ringan tapi akurat
embed_model = HuggingFaceEmbedding(model_name="intfloat/multilingual-e5-small")

# Set global
Settings.llm = llm
Settings.embed_model = embed_model

@app.get("/")
def home():
    return {"message": "PDF Summarizer Profesional (Qwen2.5-3B) siap digunakan!"}

@app.post("/summarize-pdf")
async def summarize_pdf(files: list[UploadFile] = File(...)):
    # Validasi awal
    if not files:
        raise HTTPException(status_code=400, detail="Tidak ada file yang diunggah")

    tmp_paths = []
    
    try:
        # Simpan semua file
        for file in files:
            if not file.filename.lower().endswith(".pdf"):
                continue # Skip non-pdf
            
            with tempfile.NamedTemporaryFile(delete=False, suffix=".pdf") as tmp:
                contents = await file.read()
                tmp.write(contents)
                tmp_paths.append(tmp.name)

        if not tmp_paths:
            raise HTTPException(status_code=400, detail="Semua file harus berformat PDF")

        # Baca semua PDF
        documents = SimpleDirectoryReader(input_files=tmp_paths).load_data()
        if not documents or not any(doc.text.strip() for doc in documents):
            raise HTTPException(status_code=400, detail="Tidak ada teks yang dapat dibaca di dokumen-dokumen ini")

        # Chunking optimal
        splitter = SentenceSplitter(chunk_size=768, chunk_overlap=120)
        index = VectorStoreIndex.from_documents(documents, transformations=[splitter])

        # Query engine
        query_engine = index.as_query_engine(
            response_mode="compact",
            similarity_top_k=9,
        )

        # Prompt
        prompt = """Buatlah SATU Ringkasan Eksekutif Gabungan dari seluruh dokumen yang diberikan. 
Ringkasan ini harus mencakup poin-poin penting dari semua laporan yang ada, digabungkan menjadi narasi yang kohesif.

Ketentuan:
- Mulai langsung dengan judul **Ringkasan Eksekutif Gabungan**
- Gunakan subjudul jika relevan (Latar Belakang, Pelaksanaan, Hasil, Evaluasi).
- Panjang total: 400-600 kata.
- Gabungkan informasi yang tumpang tindih dari berbagai dokumen.
- Gunakan bahasa Indonesia formal dan profesional.
- Hanya gunakan fakta dari dokumen.
"""

        response = query_engine.query(prompt)
        raw_summary = str(response).strip()

        # === POST-PROCESSING (Sama seperti sebelumnya) ===
        paragraphs = [p.strip() for p in raw_summary.split("\n\n") if p.strip()]
        cleaned_paragraphs = []
        seen_content = set()
        has_title = False

        for para in paragraphs:
            if para.startswith("**Ringkasan Eksekutif Gabungan**"):
                if not has_title:
                    cleaned_paragraphs.append("**Ringkasan Eksekutif Gabungan**")
                    has_title = True
                para = para.replace("**Ringkasan Eksekutif Gabungan**", "").strip()
            
            if not para: continue
            
            normalized = " ".join(para.lower().split())
            if normalized in seen_content: continue
            
            seen_content.add(normalized)
            cleaned_paragraphs.append(para)

        final_summary = "\n\n".join(cleaned_paragraphs)
        if not final_summary.startswith("**Ringkasan Eksekutif Gabungan**"):
             final_summary = "**Ringkasan Eksekutif Gabungan**\n\n" + final_summary

        return {
            "file_count": len(tmp_paths),
            "summary": final_summary,
            "model": "Qwen2.5-3B-Instruct (CPU only)",
            "info": "Ringkasan gabungan multi-dokumen"
        }

    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error saat pemrosesan: {str(e)}")
    finally:
        # Cleanup
        for p in tmp_paths:
            if os.path.exists(p):
                os.unlink(p)

@app.get("/health")
def health():
    return {"status": "OK", "model": "Qwen2.5-3B-Instruct loaded"}